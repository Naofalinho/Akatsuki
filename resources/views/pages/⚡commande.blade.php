<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Passer la commande')] class extends Component
{
    public array $cart = [];

    // Informations client
    public string $customer_name = '';
    public string $email = '';
    public string $phone = '';

    // Adresse
    public string $country = 'Bénin';
    public string $city = '';
    public string $neighborhood = '';
    public string $address = '';
    public string $landmark = '';

    // Livraison
    public string $delivery_method = 'home_delivery';

    // Paiement
    public string $payment_method = 'cash_on_delivery';

    // Note
    public string $customer_note = '';

    public bool $orderCreated = false;

    public ?string $orderNumber = null;

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);

        if (empty($this->cart)) {
            $this->redirectRoute('panier');

            return;
        }

        $user = Auth::user();

        if ($user) {
            $this->customer_name = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->phone = $user->phone ?? '';
        }
    }

    /**
     * Recalculer le sous-total directement depuis le panier.
     */
    public function getSubtotalProperty(): float
    {
        return (float) collect($this->cart)->sum(function ($item) {
            return (float) ($item['price'] ?? 0)
                * (int) ($item['quantity'] ?? 0);
        });
    }

    /**
     * Frais de livraison.
     */
    public function getDeliveryFeeProperty(): float
    {
        if ($this->delivery_method === 'pickup') {
            return 0;
        }

        // Livraison à domicile.
        return 1500;
    }

    /**
     * Total final.
     */
    public function getTotalProperty(): float
    {
        return $this->subtotal + $this->deliveryFee;
    }

    /**
     * Nombre d'articles.
     */
    public function getNombreArticlesProperty(): int
    {
        return (int) collect($this->cart)
            ->sum('quantity');
    }

    /**
     * Modifier le mode de livraison.
     */
    public function updatedDeliveryMethod(): void
    {
        $this->resetValidation();
    }

    /**
     * Passer la commande.
     */
    public function placeOrder(): void
    {
        if ($this->orderCreated) {
            return;
        }

        $this->cart = session()->get('cart', []);

        if (empty($this->cart)) {
            $this->addError(
                'cart',
                'Votre panier est vide.'
            );

            return;
        }

        $rules = [
            'customer_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'min:8',
                'max:30',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'neighborhood' => [
                'required',
                'string',
                'max:150',
            ],

            'address' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],

            'landmark' => [
                'nullable',
                'string',
                'max:255',
            ],

            'delivery_method' => [
                'required',
                'in:home_delivery,pickup',
            ],

            'payment_method' => [
                'required',
                'in:cash_on_delivery,mtn_momo,moov_money',
            ],

            'customer_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];

        $validated = $this->validate($rules);

        $order = DB::transaction(function () use ($validated) {

            /*
             * Recalcul du panier depuis la base de données.
             *
             * Cela évite de faire confiance au prix envoyé
             * par le navigateur.
             */
            $subtotal = 0;

            $products = [];

            foreach ($this->cart as $cartId => $item) {

                $productId = (int) $cartId;

                $product = Product::query()
                    ->lockForUpdate()
                    ->find($productId);

                if (!$product) {
                    throw new \RuntimeException(
                        "Le produit « {$item['name']} » n'existe plus."
                    );
                }

                if (!$product->is_active) {
                    throw new \RuntimeException(
                        "Le produit « {$product->name} » n'est plus disponible."
                    );
                }

                $quantity = (int) ($item['quantity'] ?? 0);

                if ($quantity < 1) {
                    throw new \RuntimeException(
                        "Quantité invalide pour le produit « {$product->name} »."
                    );
                }

                if ($quantity > $product->stock) {
                    throw new \RuntimeException(
                        "Stock insuffisant pour « {$product->name} »."
                    );
                }

                $price = (float) $product->price;

                $itemSubtotal = $price * $quantity;

                $subtotal += $itemSubtotal;

                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $deliveryFee = $validated['delivery_method'] === 'home_delivery'
                ? 1500
                : 0;

            $total = $subtotal + $deliveryFee;

            /*
             * Numéro de commande unique.
             *
             * Exemple :
             * AKA-20260930-AB12CD
             */
            do {
                $orderNumber = 'AKA-'
                    . now()->format('Ymd')
                    . '-'
                    . strtoupper(Str::random(6));

            } while (
                Order::where('order_number', $orderNumber)->exists()
            );

            $order = Order::create([
                'user_id' => Auth::id(),

                'order_number' => $orderNumber,

                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],

                'country' => $validated['country'],
                'city' => $validated['city'],
                'neighborhood' => $validated['neighborhood'],
                'address' => $validated['address'],
                'landmark' => $validated['landmark'] ?: null,

                'delivery_method' => $validated['delivery_method'],

                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',

                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,

                'status' => 'pending',

                'customer_note' => $validated['customer_note'] ?: null,
            ]);

            /*
             * Création des lignes de commande
             * et diminution du stock.
             */
            foreach ($products as $item) {

                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product->decrement(
                    'stock',
                    $item['quantity']
                );
            }

            return $order;
        });

        /*
         * Commande terminée :
         * on vide le panier.
         */
        session()->forget('cart');

        $this->cart = [];

        $this->dispatch('cart-updated');

        $this->orderNumber = $order->order_number;

        $this->orderCreated = true;
    }
};
?>

<div>

    @section('title', 'Akatsuki - Commander')

    <main class="container py-5">

        {{-- ========================================== --}}
        {{-- SUCCÈS --}}
        {{-- ========================================== --}}

        @if($orderCreated)

            <div class="row justify-content-center">

                <div class="col-lg-7">

                    <div class="card border-0 shadow-sm text-center">

                        <div class="card-body p-5">

                            <div
                                class="mx-auto mb-4 rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                style="width:80px;height:80px;"
                            >
                                <i class="bi bi-check-lg fs-1"></i>
                            </div>

                            <h1 class="fw-bold mb-3">
                                Commande confirmée !
                            </h1>

                            <p class="text-muted mb-4">
                                Merci pour votre commande.
                                Nous allons préparer vos articles
                                dans les meilleurs délais.
                            </p>

                            <div class="bg-light rounded p-4 mb-4">

                                <p class="text-muted mb-1">
                                    Numéro de commande
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ $orderNumber }}
                                </h3>

                            </div>

                            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">

                                <a
                                    href="{{ route('catalogue') }}"
                                    class="btn btn-dark px-4 py-3"
                                >
                                    <i class="bi bi-shop me-2"></i>
                                    Continuer mes achats
                                </a>

                                <a
                                    href="{{ route('home') }}"
                                    class="btn btn-outline-dark px-4 py-3"
                                >
                                    Retour à l'accueil
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @else

            {{-- ========================================== --}}
            {{-- TITRE --}}
            {{-- ========================================== --}}

            <div class="mb-5">

                <span class="text-danger fw-bold small">
                    AKATSUKI
                </span>

                <h1 class="fw-bold mt-2 mb-2">
                    Finaliser ma commande
                </h1>

                <p class="text-muted mb-0">
                    Vérifiez vos informations avant de confirmer votre commande.
                </p>

            </div>


            {{-- ERREUR PANIER --}}

            @error('cart')

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $message }}
                </div>

            @enderror


            {{-- ERREUR GÉNÉRALE --}}

            @if($errors->has('general'))

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $errors->first('general') }}
                </div>

            @endif


            <div class="row g-4">

                {{-- ====================================== --}}
                {{-- FORMULAIRE --}}
                {{-- ====================================== --}}

                <div class="col-lg-8">

                    <form wire:submit="placeOrder">

                        {{-- INFORMATIONS CLIENT --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-body p-4">

                                <h4 class="fw-bold mb-4">
                                    <i class="bi bi-person me-2 text-danger"></i>
                                    Informations client
                                </h4>


                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Nom complet
                                        </label>

                                        <input
                                            type="text"
                                            wire:model="customer_name"
                                            class="form-control @error('customer_name') is-invalid @enderror"
                                            placeholder="Votre nom complet"
                                        >

                                        @error('customer_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Téléphone
                                        </label>

                                        <input
                                            type="tel"
                                            wire:model="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="+229 XX XX XX XX"
                                        >

                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label fw-semibold">
                                            Adresse email
                                        </label>

                                        <input
                                            type="email"
                                            wire:model="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="exemple@email.com"
                                        >

                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ADRESSE --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-body p-4">

                                <h4 class="fw-bold mb-4">
                                    <i class="bi bi-geo-alt me-2 text-danger"></i>
                                    Adresse de livraison
                                </h4>


                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Pays
                                        </label>

                                        <select
                                            wire:model="country"
                                            class="form-select @error('country') is-invalid @enderror"
                                        >
                                            <option value="Bénin">
                                                Bénin
                                            </option>

                                            <option value="Togo">
                                                Togo
                                            </option>

                                            <option value="Côte d'Ivoire">
                                                Côte d'Ivoire
                                            </option>
                                        </select>

                                        @error('country')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Ville
                                        </label>

                                        <input
                                            type="text"
                                            wire:model="city"
                                            class="form-control @error('city') is-invalid @enderror"
                                            placeholder="Ex : Cotonou"
                                        >

                                        @error('city')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label fw-semibold">
                                            Quartier
                                        </label>

                                        <input
                                            type="text"
                                            wire:model="neighborhood"
                                            class="form-control @error('neighborhood') is-invalid @enderror"
                                            placeholder="Ex : Haie Vive"
                                        >

                                        @error('neighborhood')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label fw-semibold">
                                            Adresse complète
                                        </label>

                                        <textarea
                                            wire:model="address"
                                            rows="3"
                                            class="form-control @error('address') is-invalid @enderror"
                                            placeholder="Numéro de maison, rue, bâtiment..."
                                        ></textarea>

                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label fw-semibold">
                                            Point de repère
                                            <span class="text-muted fw-normal">
                                                (facultatif)
                                            </span>
                                        </label>

                                        <input
                                            type="text"
                                            wire:model="landmark"
                                            class="form-control @error('landmark') is-invalid @enderror"
                                            placeholder="Ex : près de la pharmacie..."
                                        >

                                        @error('landmark')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- LIVRAISON --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-body p-4">

                                <h4 class="fw-bold mb-4">
                                    <i class="bi bi-truck me-2 text-danger"></i>
                                    Mode de livraison
                                </h4>


                                <label
                                    class="border rounded p-3 mb-3 w-100 d-flex align-items-center"
                                    style="cursor:pointer;"
                                >

                                    <input
                                        type="radio"
                                        wire:model.live="delivery_method"
                                        value="home_delivery"
                                        class="form-check-input me-3"
                                    >

                                    <div class="flex-grow-1">

                                        <strong>
                                            Livraison à domicile
                                        </strong>

                                        <div class="small text-muted">
                                            Recevez votre commande directement
                                            à l'adresse indiquée.
                                        </div>

                                    </div>

                                    <strong>
                                        1 500 FCFA
                                    </strong>

                                </label>


                                <label
                                    class="border rounded p-3 w-100 d-flex align-items-center"
                                    style="cursor:pointer;"
                                >

                                    <input
                                        type="radio"
                                        wire:model.live="delivery_method"
                                        value="pickup"
                                        class="form-check-input me-3"
                                    >

                                    <div class="flex-grow-1">

                                        <strong>
                                            Retrait sur place
                                        </strong>

                                        <div class="small text-muted">
                                            Retirez votre commande directement
                                            auprès d'AKATSUKI.
                                        </div>

                                    </div>

                                    <strong>
                                        Gratuit
                                    </strong>

                                </label>


                                @error('delivery_method')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- PAIEMENT --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-body p-4">

                                <h4 class="fw-bold mb-4">
                                    <i class="bi bi-wallet2 me-2 text-danger"></i>
                                    Mode de paiement
                                </h4>


                                {{-- PAIEMENT LIVRAISON --}}

                                <label
                                    class="border rounded p-3 mb-3 w-100 d-flex align-items-center"
                                    style="cursor:pointer;"
                                >

                                    <input
                                        type="radio"
                                        wire:model="payment_method"
                                        value="cash_on_delivery"
                                        class="form-check-input me-3"
                                    >

                                    <div>

                                        <strong>
                                            Paiement à la livraison
                                        </strong>

                                        <div class="small text-muted">
                                            Payez lors de la réception de votre commande.
                                        </div>

                                    </div>

                                </label>


                                {{-- MTN --}}

                                <label
                                    class="border rounded p-3 mb-3 w-100 d-flex align-items-center"
                                    style="cursor:pointer;"
                                >

                                    <input
                                        type="radio"
                                        wire:model="payment_method"
                                        value="mtn_momo"
                                        class="form-check-input me-3"
                                    >

                                    <div>

                                        <strong>
                                            MTN Mobile Money
                                        </strong>

                                        <div class="small text-muted">
                                            Le paiement Mobile Money sera intégré
                                            ultérieurement.
                                        </div>

                                    </div>

                                </label>


                                {{-- MOOV --}}

                                <label
                                    class="border rounded p-3 w-100 d-flex align-items-center"
                                    style="cursor:pointer;"
                                >

                                    <input
                                        type="radio"
                                        wire:model="payment_method"
                                        value="moov_money"
                                        class="form-check-input me-3"
                                    >

                                    <div>

                                        <strong>
                                            Moov Money
                                        </strong>

                                        <div class="small text-muted">
                                            Le paiement Mobile Money sera intégré
                                            ultérieurement.
                                        </div>

                                    </div>

                                </label>


                                @error('payment_method')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- NOTE --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-body p-4">

                                <h4 class="fw-bold mb-3">
                                    <i class="bi bi-chat-left-text me-2 text-danger"></i>
                                    Instructions
                                </h4>

                                <textarea
                                    wire:model="customer_note"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Une précision concernant votre commande ou votre livraison ?"
                                ></textarea>

                                @error('customer_note')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- CONFIRMATION --}}

                        <div class="alert alert-light border mb-4">

                            <div class="d-flex gap-3">

                                <i class="bi bi-shield-check text-success fs-4"></i>

                                <div>

                                    <strong>
                                        Commande sécurisée
                                    </strong>

                                    <p class="small text-muted mb-0 mt-1">
                                        Vérifiez vos informations et le contenu
                                        de votre commande avant confirmation.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-dark btn-lg w-100 py-3"
                            wire:loading.attr="disabled"
                        >

                            <span
                                wire:loading.remove
                                wire:target="placeOrder"
                            >
                                <i class="bi bi-check-circle me-2"></i>
                                Confirmer la commande
                            </span>

                            <span
                                wire:loading
                                wire:target="placeOrder"
                            >
                                <span
                                    class="spinner-border spinner-border-sm me-2"
                                ></span>

                                Création de la commande...
                            </span>

                        </button>

                    </form>

                </div>


                {{-- ====================================== --}}
                {{-- RÉSUMÉ --}}
                {{-- ====================================== --}}

                <div class="col-lg-4">

                    <div
                        class="card border-0 shadow-sm"
                        style="position:sticky;top:25px;"
                    >

                        <div class="card-body p-4">

                            <h4 class="fw-bold mb-4">
                                Votre commande
                            </h4>


                            {{-- PRODUITS --}}

                            <div class="mb-4">

                                @foreach($cart as $id => $produit)

                                    <div
                                        class="d-flex gap-3 mb-3"
                                        wire:key="order-product-{{ $id }}"
                                    >

                                        @if(!empty($produit['image']))

                                            <img
                                                src="{{ asset('storage/' . $produit['image']) }}"
                                                alt="{{ $produit['name'] }}"
                                                class="rounded"
                                                style="
                                                    width:65px;
                                                    height:65px;
                                                    object-fit:cover;
                                                "
                                            >

                                        @else

                                            <div
                                                class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="
                                                    width:65px;
                                                    height:65px;
                                                "
                                            >
                                                <i class="bi bi-image text-muted"></i>
                                            </div>

                                        @endif


                                        <div class="flex-grow-1">

                                            <div class="fw-semibold">
                                                {{ $produit['name'] }}
                                            </div>

                                            <div class="small text-muted">
                                                {{ $produit['quantity'] }}
                                                ×
                                                {{ number_format($produit['price'], 0, ',', ' ') }}
                                                FCFA
                                            </div>

                                        </div>


                                        <strong class="small">

                                            {{ number_format(
                                                $produit['price'] * $produit['quantity'],
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </strong>

                                    </div>

                                @endforeach

                            </div>


                            <hr>


                            {{-- NOMBRE ARTICLES --}}

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Articles
                                </span>

                                <strong>
                                    {{ $this->nombreArticles }}
                                </strong>

                            </div>


                            {{-- SOUS TOTAL --}}

                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Sous-total
                                </span>

                                <strong>
                                    {{ number_format(
                                        $this->subtotal,
                                        0,
                                        ',',
                                        ' '
                                    ) }}
                                    FCFA
                                </strong>

                            </div>


                            {{-- LIVRAISON --}}

                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Livraison
                                </span>

                                <strong>

                                    @if($this->deliveryFee > 0)

                                        {{ number_format(
                                            $this->deliveryFee,
                                            0,
                                            ',',
                                            ' '
                                        ) }}
                                        FCFA

                                    @else

                                        Gratuit

                                    @endif

                                </strong>

                            </div>


                            <hr>


                            {{-- TOTAL --}}

                            <div class="d-flex justify-content-between align-items-center">

                                <span class="fw-bold fs-5">
                                    Total
                                </span>

                                <span class="fw-bold fs-4 text-danger">

                                    {{ number_format(
                                        $this->total,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </span>

                            </div>


                            <div class="mt-4 small text-muted">

                                <i class="bi bi-lock me-1"></i>

                                Vos informations sont protégées.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </main>

</div>