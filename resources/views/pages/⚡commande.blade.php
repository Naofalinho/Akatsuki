<?php

use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.base')]
#[Title('Commande - AKATSUKI')]
class extends Component
{
    /*
    |--------------------------------------------------------------------------
    | INFORMATIONS CLIENT
    |--------------------------------------------------------------------------
    */

    public string $customerName = '';
    public string $email = '';
    public string $phone = '';

    /*
    |--------------------------------------------------------------------------
    | ADRESSE
    |--------------------------------------------------------------------------
    */

    public string $country = 'Bénin';
    public string $city = '';
    public string $neighborhood = '';
    public string $address = '';
    public string $landmark = '';

    /*
    |--------------------------------------------------------------------------
    | LIVRAISON
    |--------------------------------------------------------------------------
    */

    public string $deliveryMethod = 'home_delivery';

    /*
    |--------------------------------------------------------------------------
    | PAIEMENT
    |--------------------------------------------------------------------------
    */

    public string $paymentMethod = 'cash_on_delivery';

    /*
    |--------------------------------------------------------------------------
    | NOTE
    |--------------------------------------------------------------------------
    */

    public string $customerNote = '';

    /*
    |--------------------------------------------------------------------------
    | PANIER
    |--------------------------------------------------------------------------
    */

    public array $cart = [];

    /*
    |--------------------------------------------------------------------------
    | COMMANDE
    |--------------------------------------------------------------------------
    */

    public bool $orderCreated = false;

    public ?string $orderNumber = null;


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->loadCart();

        /*
        |--------------------------------------------------------------------------
        | Si le panier est vide
        |--------------------------------------------------------------------------
        */

        if (empty($this->cart)) {
            $this->redirectRoute('panier');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Pré-remplir les informations du client connecté
        |--------------------------------------------------------------------------
        */

        if (Auth::check()) {
            $user = Auth::user();

            $this->customerName = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->phone = $user->phone ?? '';
        }

        /*
        |--------------------------------------------------------------------------
        | Choisir automatiquement le premier moyen de paiement disponible
        |--------------------------------------------------------------------------
        */

        $this->setDefaultPaymentMethod();
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LE PANIER
    |--------------------------------------------------------------------------
    */

    public function loadCart(): void
    {
        $cart = session()->get('cart', []);

        if (is_array($cart)) {
            $this->cart = $cart;
        } else {
            $this->cart = [];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MOYENS DE PAIEMENT DISPONIBLES
    |--------------------------------------------------------------------------
    */

    public function getAvailablePaymentMethodsProperty(): array
    {
        return [
            'cash_on_delivery' => (string) Setting::getValue(
                'payment_cash_on_delivery',
                '1'
            ) === '1',

            'mtn_momo' => (string) Setting::getValue(
                'payment_mtn_momo',
                '1'
            ) === '1',

            'moov_money' => (string) Setting::getValue(
                'payment_moov_money',
                '1'
            ) === '1',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | CHOISIR LE PAIEMENT PAR DÉFAUT
    |--------------------------------------------------------------------------
    */

    public function setDefaultPaymentMethod(): void
    {
        $methods = $this->availablePaymentMethods;

        /*
        |--------------------------------------------------------------------------
        | Conserver le moyen actuel s'il est disponible
        |--------------------------------------------------------------------------
        */

        if (
            isset($methods[$this->paymentMethod]) &&
            $methods[$this->paymentMethod] === true
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Choisir le premier moyen disponible
        |--------------------------------------------------------------------------
        */

        foreach ($methods as $method => $enabled) {
            if ($enabled) {
                $this->paymentMethod = $method;

                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Aucun paiement disponible
        |--------------------------------------------------------------------------
        */

        $this->paymentMethod = '';
    }


    /*
    |--------------------------------------------------------------------------
    | FRAIS DE LIVRAISON
    |--------------------------------------------------------------------------
    */

    public function getDeliveryFeeProperty(): float
    {
        /*
        |--------------------------------------------------------------------------
        | Retrait en boutique = gratuit
        |--------------------------------------------------------------------------
        */

        if ($this->deliveryMethod === 'pickup') {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Livraison à domicile
        |--------------------------------------------------------------------------
        */

        return (float) Setting::getValue(
            'delivery_fee',
            1500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SOUS-TOTAL
    |--------------------------------------------------------------------------
    */

    public function getSubtotalProperty(): float
    {
        $subtotal = 0;

        foreach ($this->cart as $item) {
            $price = (float) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);

            $subtotal += $price * $quantity;
        }

        return $subtotal;
    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    */

    public function getTotalProperty(): float
    {
        return $this->subtotal + $this->deliveryFee;
    }


    /*
    |--------------------------------------------------------------------------
    | NOMBRE D'ARTICLES
    |--------------------------------------------------------------------------
    */

    public function getCartCountProperty(): int
    {
        return collect($this->cart)->sum(
            fn ($item) => (int) ($item['quantity'] ?? 0)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT DE LIVRAISON
    |--------------------------------------------------------------------------
    */

    public function updatedDeliveryMethod(): void
    {
        $this->resetValidation([
            'city',
            'neighborhood',
            'address',
            'landmark',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT DE PAIEMENT
    |--------------------------------------------------------------------------
    */

    public function updatedPaymentMethod(): void
    {
        $this->resetValidation('paymentMethod');
    }


    /*
    |--------------------------------------------------------------------------
    | PASSER LA COMMANDE
    |--------------------------------------------------------------------------
    */

    public function placeOrder(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Recharger le panier
        |--------------------------------------------------------------------------
        */

        $this->loadCart();

        if (empty($this->cart)) {
            $this->addError(
                'cart',
                'Votre panier est vide.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Validation des informations
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'customerName' => [
                'required',
                'string',
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
                'max:50',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Adresse obligatoire uniquement pour la livraison
            |--------------------------------------------------------------------------
            */

            'city' => [
                'required_if:deliveryMethod,home_delivery',
                'nullable',
                'string',
                'max:100',
            ],

            'neighborhood' => [
                'required_if:deliveryMethod,home_delivery',
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'required_if:deliveryMethod,home_delivery',
                'nullable',
                'string',
                'max:1000',
            ],

            'landmark' => [
                'nullable',
                'string',
                'max:255',
            ],

            'deliveryMethod' => [
                'required',
                'in:home_delivery,pickup',
            ],

            'paymentMethod' => [
                'required',
                'in:cash_on_delivery,mtn_momo,moov_money',
            ],

            'customerNote' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier le moyen de paiement
        |--------------------------------------------------------------------------
        */

        $availablePayments = $this->availablePaymentMethods;

        if (
            !isset($availablePayments[$this->paymentMethod]) ||
            !$availablePayments[$this->paymentMethod]
        ) {
            $this->addError(
                'paymentMethod',
                'Ce moyen de paiement n’est actuellement pas disponible.'
            );

            $this->setDefaultPaymentMethod();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Vérification supplémentaire de l'adresse
        |--------------------------------------------------------------------------
        */

        if ($this->deliveryMethod === 'home_delivery') {

            if (
                empty(trim($this->city)) ||
                empty(trim($this->neighborhood)) ||
                empty(trim($this->address))
            ) {
                $this->addError(
                    'address',
                    'Veuillez renseigner votre adresse de livraison.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Création de la commande
        |--------------------------------------------------------------------------
        */

        try {

            $order = DB::transaction(function () {

                /*
                |--------------------------------------------------------------------------
                | Produits vérifiés depuis la base
                |--------------------------------------------------------------------------
                */

                $products = [];

                foreach ($this->cart as $item) {

                    $productId = (int) ($item['id'] ?? 0);

                    $quantity = (int) ($item['quantity'] ?? 0);

                    /*
                    |--------------------------------------------------------------------------
                    | Vérifier les données du panier
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $productId <= 0 ||
                        $quantity <= 0
                    ) {
                        throw new \Exception(
                            'Un article de votre panier est invalide.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Verrouiller le produit
                    |--------------------------------------------------------------------------
                    |
                    | Cela évite que deux clients commandent simultanément
                    | le même dernier article.
                    |
                    */

                    $product = Product::query()
                        ->whereKey($productId)
                        ->lockForUpdate()
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Produit inexistant
                    |--------------------------------------------------------------------------
                    */

                    if (!$product) {
                        throw new \Exception(
                            'Un produit de votre panier n’existe plus.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Produit désactivé
                    |--------------------------------------------------------------------------
                    */

                    if (!$product->is_active) {
                        throw new \Exception(
                            "Le produit « {$product->name} » n'est plus disponible."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Rupture de stock
                    |--------------------------------------------------------------------------
                    */

                    if ($product->stock <= 0) {
                        throw new \Exception(
                            "Le produit « {$product->name} » est en rupture de stock."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Quantité supérieure au stock
                    |--------------------------------------------------------------------------
                    */

                    if ($quantity > $product->stock) {
                        throw new \Exception(
                            "Le stock disponible pour « {$product->name} » est de {$product->stock}."
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Ajouter le produit vérifié
                    |--------------------------------------------------------------------------
                    */

                    $products[$productId] = [
                        'product' => $product,
                        'quantity' => $quantity,
                    ];
                }


                /*
                |--------------------------------------------------------------------------
                | Calculer le sous-total avec les vrais prix DB
                |--------------------------------------------------------------------------
                */

                $subtotal = 0;

                foreach ($products as $data) {

                    $product = $data['product'];

                    $quantity = $data['quantity'];

                    $subtotal +=
                        (float) $product->price * $quantity;
                }


                /*
                |--------------------------------------------------------------------------
                | Frais de livraison
                |--------------------------------------------------------------------------
                */

                $deliveryFee = $this->deliveryMethod === 'pickup'
                    ? 0
                    : (float) Setting::getValue(
                        'delivery_fee',
                        1500
                    );


                /*
                |--------------------------------------------------------------------------
                | Total
                |--------------------------------------------------------------------------
                */

                $total = $subtotal + $deliveryFee;


                /*
                |--------------------------------------------------------------------------
                | Générer un numéro de commande unique
                |--------------------------------------------------------------------------
                */

                do {

                    $orderNumber =
                        'AKA-' .
                        now()->format('Ymd') .
                        '-' .
                        strtoupper(Str::random(6));

                } while (
                    Order::where(
                        'order_number',
                        $orderNumber
                    )->exists()
                );


                /*
                |--------------------------------------------------------------------------
                | Créer la commande
                |--------------------------------------------------------------------------
                */

                $order = Order::create([

                    'user_id' => Auth::id(),

                    'order_number' => $orderNumber,

                    'customer_name' => trim(
                        $this->customerName
                    ),

                    'email' => trim(
                        $this->email
                    ),

                    'phone' => trim(
                        $this->phone
                    ),

                    'country' => $this->country,

                    /*
                    |--------------------------------------------------------------------------
                    | Pour le retrait, on peut enregistrer des valeurs vides
                    |--------------------------------------------------------------------------
                    */

                    'city' => $this->deliveryMethod === 'pickup'
                        ? ''
                        : trim($this->city),

                    'neighborhood' => $this->deliveryMethod === 'pickup'
                        ? ''
                        : trim($this->neighborhood),

                    'address' => $this->deliveryMethod === 'pickup'
                        ? ''
                        : trim($this->address),

                    'landmark' => $this->deliveryMethod === 'pickup'
                        ? null
                        : (
                            trim($this->landmark) !== ''
                                ? trim($this->landmark)
                                : null
                        ),

                    'delivery_method' =>
                        $this->deliveryMethod,

                    'payment_method' =>
                        $this->paymentMethod,

                    /*
                    |--------------------------------------------------------------------------
                    | Paiement en attente
                    |--------------------------------------------------------------------------
                    */

                    'payment_status' => 'pending',

                    /*
                    |--------------------------------------------------------------------------
                    | Montants
                    |--------------------------------------------------------------------------
                    */

                    'subtotal' => $subtotal,

                    'delivery_fee' => $deliveryFee,

                    'total' => $total,

                    /*
                    |--------------------------------------------------------------------------
                    | Statut de commande
                    |--------------------------------------------------------------------------
                    */

                    'status' => 'pending',

                    /*
                    |--------------------------------------------------------------------------
                    | Note
                    |--------------------------------------------------------------------------
                    */

                    'customer_note' =>
                        trim($this->customerNote) !== ''
                            ? trim($this->customerNote)
                            : null,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Créer les lignes de commande
                |--------------------------------------------------------------------------
                */

                foreach ($products as $data) {

                    $product = $data['product'];

                    $quantity = $data['quantity'];

                    $itemSubtotal =
                        (float) $product->price * $quantity;


                    $order->items()->create([

                        'product_id' =>
                            $product->id,

                        'product_name' =>
                            $product->name,

                        'price' =>
                            $product->price,

                        'quantity' =>
                            $quantity,

                        'subtotal' =>
                            $itemSubtotal,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Décrémenter le stock
                    |--------------------------------------------------------------------------
                    */

                    $product->decrement(
                        'stock',
                        $quantity
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Vider le panier
                |--------------------------------------------------------------------------
                */

                session()->forget('cart');


                /*
                |--------------------------------------------------------------------------
                | Actualiser le compteur du panier
                |--------------------------------------------------------------------------
                */

                $this->dispatch('cart-updated');


                /*
                |--------------------------------------------------------------------------
                | Informations de confirmation
                |--------------------------------------------------------------------------
                */

                $this->orderNumber =
                    $orderNumber;

                $this->orderCreated =
                    true;

                return $order;
            });

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | En développement
            |--------------------------------------------------------------------------
            |
            | On enregistre l'erreur dans les logs et on affiche un
            | message propre au client.
            |
            */

            report($e);

            $this->addError(
                'order',
                'Une erreur est survenue lors de la création de votre commande. Veuillez réessayer.'
            );

            return;
        }

        $settings = Cache::get('akatsuki_settings', []);

        if (
            is_array($settings) &&
            ($settings['orderNotifications'] ?? true)
        ) {
            try {
                Notification::send(
                    User::query()->where('role', 'admin')->get(),
                    new NewOrderNotification($order),
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
};
?>


{{-- ================================================================== --}}
{{-- PAGE COMMANDE --}}
{{-- ================================================================== --}}

<div class="checkout-page">
    @section('title', 'Akatsuki - Commande')

    {{-- ================================================================== --}}
    {{-- COMMANDE CRÉÉE --}}
    {{-- ================================================================== --}}

    @if ($orderCreated)

        <div class="checkout-success">

            <div class="checkout-success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <span class="checkout-success-eyebrow">
                COMMANDE CONFIRMÉE
            </span>

            <h1>
                Merci pour votre commande !
            </h1>

            <p>
                Votre commande a bien été enregistrée.
                Notre équipe va traiter votre commande dans les meilleurs délais.
            </p>


            @if ($orderNumber)

                <div class="checkout-order-number">

                    <span>
                        Numéro de commande
                    </span>

                    <strong>
                        {{ $orderNumber }}
                    </strong>

                </div>

            @endif


            <div class="checkout-success-actions">

                <a
                    href="{{ route('catalogue') }}"
                    class="btn btn-dark"
                >
                    <i class="bi bi-shop me-2"></i>
                    Continuer mes achats
                </a>


                @if ($orderNumber)

                    <a
                        href="{{ route('commandes.confirmation', $orderNumber) }}"
                        class="btn btn-outline-dark"
                    >
                        <i class="bi bi-receipt me-2"></i>
                        Voir la confirmation
                    </a>

                @endif

            </div>

        </div>


    {{-- ================================================================== --}}
    {{-- FORMULAIRE --}}
    {{-- ================================================================== --}}

    @else

        {{-- ================================================================== --}}
        {{-- EN-TÊTE --}}
        {{-- ================================================================== --}}

        <div class="checkout-header">

            <span class="checkout-eyebrow">
                AKATSUKI
            </span>

            <h1>
                Finaliser ma commande
            </h1>

            <p>
                Vérifiez vos informations avant de confirmer votre commande.
            </p>

        </div>


        {{-- ================================================================== --}}
        {{-- ERREUR COMMANDE --}}
        {{-- ================================================================== --}}

        @if ($errors->has('order'))

            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">

                <i class="bi bi-exclamation-triangle-fill mt-1"></i>

                <div>
                    {{ $errors->first('order') }}
                </div>

            </div>

        @endif


        {{-- ================================================================== --}}
        {{-- ERREUR PANIER --}}
        {{-- ================================================================== --}}

        @if ($errors->has('cart'))

            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">

                <i class="bi bi-cart-x-fill mt-1"></i>

                <div>
                    {{ $errors->first('cart') }}
                </div>

            </div>

        @endif


        {{-- ================================================================== --}}
        {{-- FORMULAIRE --}}
        {{-- ================================================================== --}}

        <form wire:submit="placeOrder">

            <div class="row g-4">


                {{-- ========================================================== --}}
                {{-- COLONNE GAUCHE --}}
                {{-- ========================================================== --}}

                <div class="col-lg-7">


                    {{-- ====================================================== --}}
                    {{-- INFORMATIONS PERSONNELLES --}}
                    {{-- ====================================================== --}}

                    <div class="checkout-card">

                        <div class="checkout-card-header">

                            <div class="checkout-card-icon">
                                <i class="bi bi-person"></i>
                            </div>

                            <div>

                                <h2>
                                    Informations personnelles
                                </h2>

                                <p>
                                    Vos coordonnées pour recevoir votre commande.
                                </p>

                            </div>

                        </div>


                        <div class="checkout-card-body">

                            <div class="row g-3">


                                {{-- NOM --}}
                                <div class="col-md-6">

                                    <label class="form-label">

                                        Nom complet

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>


                                    <input
                                        type="text"
                                        wire:model="customerName"
                                        class="form-control @error('customerName') is-invalid @enderror"
                                        placeholder="Votre nom complet"
                                    >


                                    @error('customerName')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- TELEPHONE --}}
                                <div class="col-md-6">

                                    <label class="form-label">

                                        Téléphone

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>


                                    <input
                                        type="text"
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


                                {{-- EMAIL --}}
                                <div class="col-12">

                                    <label class="form-label">

                                        Adresse email

                                        <span class="text-danger">
                                            *
                                        </span>

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


                    {{-- ====================================================== --}}
                    {{-- LIVRAISON --}}
                    {{-- ====================================================== --}}

                    <div class="checkout-card">

                        <div class="checkout-card-header">

                            <div class="checkout-card-icon">
                                <i class="bi bi-truck"></i>
                            </div>

                            <div>

                                <h2>
                                    Livraison
                                </h2>

                                <p>
                                    Choisissez comment recevoir votre commande.
                                </p>

                            </div>

                        </div>


                        <div class="checkout-card-body">


                            {{-- OPTIONS DE LIVRAISON --}}
                            <div class="delivery-options">


                                {{-- LIVRAISON DOMICILE --}}
                                <label
                                    class="delivery-option {{ $deliveryMethod === 'home_delivery' ? 'active' : '' }}"
                                >

                                    <input
                                        type="radio"
                                        wire:model.live="deliveryMethod"
                                        value="home_delivery"
                                    >


                                    <div class="delivery-option-icon">

                                        <i class="bi bi-truck"></i>

                                    </div>


                                    <div class="delivery-option-content">

                                        <strong>
                                            Livraison à domicile
                                        </strong>

                                        <span>
                                            Recevez votre commande à votre adresse.
                                        </span>

                                    </div>


                                    <div class="delivery-option-price">

                                        {{ number_format($this->deliveryFee, 0, ',', ' ') }}
                                        FCFA

                                    </div>

                                </label>


                                {{-- RETRAIT --}}
                                <label
                                    class="delivery-option {{ $deliveryMethod === 'pickup' ? 'active' : '' }}"
                                >

                                    <input
                                        type="radio"
                                        wire:model.live="deliveryMethod"
                                        value="pickup"
                                    >


                                    <div class="delivery-option-icon">

                                        <i class="bi bi-shop"></i>

                                    </div>


                                    <div class="delivery-option-content">

                                        <strong>
                                            Retrait en boutique
                                        </strong>

                                        <span>
                                            Retirez votre commande directement en boutique.
                                        </span>

                                    </div>


                                    <div class="delivery-option-price">

                                        Gratuit

                                    </div>

                                </label>

                            </div>


                            {{-- ================================================== --}}
                            {{-- ADRESSE LIVRAISON --}}
                            {{-- ================================================== --}}

                            @if ($deliveryMethod === 'home_delivery')

                                <div class="row g-3 mt-3">


                                    {{-- PAYS --}}
                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Pays
                                        </label>


                                        <input
                                            type="text"
                                            wire:model="country"
                                            class="form-control"
                                            readonly
                                        >

                                    </div>


                                    {{-- VILLE --}}
                                    <div class="col-md-6">

                                        <label class="form-label">

                                            Ville

                                            <span class="text-danger">
                                                *
                                            </span>

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


                                    {{-- QUARTIER --}}
                                    <div class="col-12">

                                        <label class="form-label">

                                            Quartier

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>


                                        <input
                                            type="text"
                                            wire:model="neighborhood"
                                            class="form-control @error('neighborhood') is-invalid @enderror"
                                            placeholder="Ex : Akpakpa"
                                        >


                                        @error('neighborhood')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>


                                    {{-- ADRESSE --}}
                                    <div class="col-12">

                                        <label class="form-label">

                                            Adresse

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>


                                        <textarea
                                            wire:model="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            rows="3"
                                            placeholder="Rue, maison, immeuble..."
                                        ></textarea>


                                        @error('address')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>


                                    {{-- POINT DE REPERE --}}
                                    <div class="col-12">

                                        <label class="form-label">
                                            Point de repère
                                        </label>


                                        <input
                                            type="text"
                                            wire:model="landmark"
                                            class="form-control"
                                            placeholder="Ex : près de la pharmacie..."
                                        >

                                    </div>

                                </div>


                            @else

                                {{-- ================================================== --}}
                                {{-- INFORMATIONS RETRAIT --}}
                                {{-- ================================================== --}}

                                <div class="pickup-info mt-3">

                                    <i class="bi bi-info-circle"></i>

                                    <div>

                                        <strong>
                                            Retrait en boutique
                                        </strong>

                                        <p>
                                            Votre commande sera préparée et vous pourrez
                                            la retirer directement auprès de notre boutique.
                                        </p>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- ====================================================== --}}
                    {{-- PAIEMENT --}}
                    {{-- ====================================================== --}}

                    <div class="checkout-card">

                        <div class="checkout-card-header">

                            <div class="checkout-card-icon">

                                <i class="bi bi-credit-card"></i>

                            </div>


                            <div>

                                <h2>
                                    Mode de paiement
                                </h2>

                                <p>
                                    Sélectionnez votre moyen de paiement.
                                </p>

                            </div>

                        </div>


                        <div class="checkout-card-body">

                            <div class="payment-options">


                                {{-- ================================================== --}}
                                {{-- PAIEMENT A LA LIVRAISON --}}
                                {{-- ================================================== --}}

                                @if ($this->availablePaymentMethods['cash_on_delivery'])

                                    <label
                                        class="payment-option {{ $paymentMethod === 'cash_on_delivery' ? 'active' : '' }}"
                                    >

                                        <input
                                            type="radio"
                                            wire:model.live="paymentMethod"
                                            value="cash_on_delivery"
                                        >


                                        <div class="payment-option-icon">

                                            <i class="bi bi-cash-stack"></i>

                                        </div>


                                        <div class="payment-option-content">

                                            <strong>
                                                Paiement à la livraison
                                            </strong>

                                            <span>
                                                Payez lorsque vous recevez votre commande.
                                            </span>

                                        </div>

                                    </label>

                                @endif


                                {{-- ================================================== --}}
                                {{-- MTN MOMO --}}
                                {{-- ================================================== --}}

                                @if ($this->availablePaymentMethods['mtn_momo'])

                                    <label
                                        class="payment-option {{ $paymentMethod === 'mtn_momo' ? 'active' : '' }}"
                                    >

                                        <input
                                            type="radio"
                                            wire:model.live="paymentMethod"
                                            value="mtn_momo"
                                        >


                                        <div class="payment-option-icon">

                                            <i class="bi bi-phone"></i>

                                        </div>


                                        <div class="payment-option-content">

                                            <strong>
                                                MTN Mobile Money
                                            </strong>

                                            <span>
                                                Paiement via MTN MoMo.
                                            </span>

                                        </div>

                                    </label>

                                @endif


                                {{-- ================================================== --}}
                                {{-- MOOV MONEY --}}
                                {{-- ================================================== --}}

                                @if ($this->availablePaymentMethods['moov_money'])

                                    <label
                                        class="payment-option {{ $paymentMethod === 'moov_money' ? 'active' : '' }}"
                                    >

                                        <input
                                            type="radio"
                                            wire:model.live="paymentMethod"
                                            value="moov_money"
                                        >


                                        <div class="payment-option-icon">

                                            <i class="bi bi-phone"></i>

                                        </div>


                                        <div class="payment-option-content">

                                            <strong>
                                                Moov Money
                                            </strong>

                                            <span>
                                                Paiement via Moov Money.
                                            </span>

                                        </div>

                                    </label>

                                @endif

                            </div>


                            {{-- ERREUR PAIEMENT --}}
                            @error('paymentMethod')

                                <div class="text-danger small mt-3">
                                    {{ $message }}
                                </div>

                            @enderror


                            {{-- AUCUN PAIEMENT --}}
                            @if (
                                !$this->availablePaymentMethods['cash_on_delivery'] &&
                                !$this->availablePaymentMethods['mtn_momo'] &&
                                !$this->availablePaymentMethods['moov_money']
                            )

                                <div class="alert alert-warning mt-3 mb-0">

                                    <i class="bi bi-exclamation-triangle me-2"></i>

                                    Aucun moyen de paiement n'est actuellement disponible.
                                    Contactez l'administration de la boutique.

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- ====================================================== --}}
                    {{-- NOTE --}}
                    {{-- ====================================================== --}}

                    <div class="checkout-card">

                        <div class="checkout-card-header">

                            <div class="checkout-card-icon">

                                <i class="bi bi-chat-left-text"></i>

                            </div>


                            <div>

                                <h2>
                                    Informations supplémentaires
                                </h2>

                                <p>
                                    Une précision à transmettre à notre équipe ?
                                </p>

                            </div>

                        </div>


                        <div class="checkout-card-body">

                            <label class="form-label">
                                Note de commande
                            </label>


                            <textarea
                                wire:model="customerNote"
                                class="form-control @error('customerNote') is-invalid @enderror"
                                rows="4"
                                placeholder="Instructions particulières pour votre commande..."
                            ></textarea>


                            @error('customerNote')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ========================================================== --}}
                {{-- COLONNE DROITE --}}
                {{-- ========================================================== --}}

                <div class="col-lg-5">


                    {{-- ====================================================== --}}
                    {{-- RÉCAPITULATIF --}}
                    {{-- ====================================================== --}}

                    <div class="checkout-summary-card">


                        {{-- HEADER --}}
                        <div class="checkout-summary-header">

                            <div>

                                <span>
                                    RÉCAPITULATIF
                                </span>

                                <h2>
                                    Votre commande
                                </h2>

                            </div>


                            <div class="checkout-cart-count">

                                {{ $this->cartCount }}

                                article{{ $this->cartCount > 1 ? 's' : '' }}

                            </div>

                        </div>


                        {{-- ================================================== --}}
                        {{-- ARTICLES --}}
                        {{-- ================================================== --}}

                        <div class="checkout-items">

                            @foreach ($cart as $item)

                                <div class="checkout-item">


                                    {{-- IMAGE --}}
                                    <div class="checkout-item-image">

                                        @if (!empty($item['image']))

                                            <img
                                                src="{{ asset('storage/' . $item['image']) }}"
                                                alt="{{ $item['name'] ?? 'Produit' }}"
                                            >

                                        @else

                                            <div class="checkout-item-placeholder">

                                                <i class="bi bi-image"></i>

                                            </div>

                                        @endif

                                    </div>


                                    {{-- INFORMATIONS --}}
                                    <div class="checkout-item-info">

                                        <strong>
                                            {{ $item['name'] ?? 'Produit' }}
                                        </strong>

                                        <span>

                                            Quantité :

                                            {{ $item['quantity'] ?? 1 }}

                                        </span>

                                    </div>


                                    {{-- PRIX --}}
                                    <div class="checkout-item-price">

                                        {{
                                            number_format(
                                                (float) ($item['price'] ?? 0)
                                                *
                                                (int) ($item['quantity'] ?? 1),
                                                0,
                                                ',',
                                                ' '
                                            )
                                        }}

                                        FCFA

                                    </div>

                                </div>

                            @endforeach

                        </div>


                        {{-- ================================================== --}}
                        {{-- RÉSUMÉ PRIX --}}
                        {{-- ================================================== --}}

                        <div class="checkout-summary-body">


                            {{-- SOUS-TOTAL --}}
                            <div class="checkout-summary-row">

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
                            <div class="checkout-summary-row">

                                <span>
                                    Livraison
                                </span>

                                <strong>

                                    @if ($deliveryMethod === 'pickup')

                                        Gratuit

                                    @else

                                        {{ number_format(
                                            $this->deliveryFee,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    @endif

                                </strong>

                            </div>


                            {{-- SEPARATEUR --}}
                            <div class="checkout-summary-divider"></div>


                            {{-- TOTAL --}}
                            <div class="checkout-summary-total">

                                <span>
                                    Total
                                </span>

                                <strong>

                                    {{ number_format(
                                        $this->total,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================== --}}
                        {{-- FOOTER --}}
                        {{-- ================================================== --}}

                        <div class="checkout-summary-footer">


                            {{-- BOUTON --}}
                            <button
                                type="submit"
                                class="checkout-submit-btn"
                                wire:loading.attr="disabled"
                                wire:target="placeOrder"
                            >

                                {{-- NORMAL --}}
                                <span wire:loading.remove wire:target="placeOrder">

                                    <i class="bi bi-check2-circle"></i>

                                    Confirmer la commande

                                </span>


                                {{-- CHARGEMENT --}}
                                <span
                                    wire:loading
                                    wire:target="placeOrder"
                                >

                                    <span class="spinner-border spinner-border-sm me-2"></span>

                                    Traitement...

                                </span>

                            </button>


                            {{-- RETOUR PANIER --}}
                            <a
                                href="{{ route('panier') }}"
                                class="checkout-back-link"
                            >

                                <i class="bi bi-arrow-left"></i>

                                Retour au panier

                            </a>


                            {{-- SECURITE --}}
                            <div class="checkout-secure">

                                <i class="bi bi-shield-check"></i>

                                <span>
                                    Vos informations sont protégées.
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    @endif

</div>