<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    public array $cart = [];

    public function mount(): void
    {
        $this->loadCart();
    }

    /**
     * Charger le panier depuis la session
     */
    public function loadCart(): void
    {
        $this->cart = session()->get('cart', []);
    }

    /**
     * Augmenter la quantité
     */
    public function increase(int $productId): void
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        $product = Product::find($productId);

        if (!$product || !$product->is_active) {
            return;
        }

        if ($this->cart[$productId]['quantity'] >= $product->stock) {
            return;
        }

        $this->cart[$productId]['quantity']++;

        $this->saveCart();
    }

    /**
     * Diminuer la quantité
     */
    public function decrease(int $productId): void
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        if ($this->cart[$productId]['quantity'] > 1) {
            $this->cart[$productId]['quantity']--;
        } else {
            unset($this->cart[$productId]);
        }

        $this->saveCart();
    }

    /**
     * Supprimer un produit
     */
    public function remove(int $productId): void
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        unset($this->cart[$productId]);

        $this->saveCart();
    }

    /**
     * Vider le panier
     */
    public function clearCart(): void
    {
        $this->cart = [];

        session()->forget('cart');

        $this->dispatch('cart-updated');
    }

    /**
     * Sauvegarder le panier
     */
    protected function saveCart(): void
    {
        session()->put('cart', $this->cart);

        $this->dispatch('cart-updated');
    }

    /**
     * Nombre total d'articles
     */
    public function getNombreArticlesProperty(): int
    {
        return (int) collect($this->cart)
            ->sum('quantity');
    }

    /**
     * Total du panier
     */
    public function getTotalProperty(): float
    {
        return (float) collect($this->cart)
            ->sum(function ($item) {
                return (float) $item['price']
                    * (int) $item['quantity'];
            });
    }
};
?>

<div>

    @section('title', 'Akatsuki - Panier')

    <main class="container py-5">

        {{-- ========================================= --}}
        {{-- EN-TÊTE --}}
        {{-- ========================================= --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h1 class="fw-bold mb-1">
                    Mon panier
                </h1>

                <p class="text-muted mb-0">
                    {{ $this->nombreArticles }}
                    article(s) dans votre panier
                </p>

            </div>


            @if(count($cart) > 0)

                <button
                    type="button"
                    wire:click="clearCart"
                    wire:confirm="Voulez-vous vraiment vider votre panier ?"
                    class="btn btn-outline-danger"
                >
                    <i class="bi bi-trash me-1"></i>

                    Vider le panier
                </button>

            @endif

        </div>


        {{-- ========================================= --}}
        {{-- PANIER NON VIDE --}}
        {{-- ========================================= --}}

        @if(count($cart) > 0)

            <div class="row g-4">


                {{-- ================================= --}}
                {{-- LISTE DES PRODUITS --}}
                {{-- ================================= --}}

                <div class="col-lg-8">

                    @foreach($cart as $id => $produit)

                        <div
                            wire:key="cart-product-{{ $id }}"
                            class="card border-0 shadow-sm mb-3"
                        >

                            <div class="card-body">

                                <div class="row align-items-center g-3">


                                    {{-- IMAGE --}}
                                    <div class="col-4 col-md-2">

                                        @if(!empty($produit['image']))

                                            <img
                                                src="{{ asset('storage/' . $produit['image']) }}"
                                                alt="{{ $produit['name'] }}"
                                                class="img-fluid rounded"
                                                style="
                                                    height:100px;
                                                    width:100%;
                                                    object-fit:cover;
                                                "
                                            >

                                        @else

                                            <div
                                                class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="height:100px;"
                                            >

                                                <i
                                                    class="bi bi-image fs-2 text-muted"
                                                ></i>

                                            </div>

                                        @endif

                                    </div>


                                    {{-- INFORMATIONS --}}
                                    <div class="col-8 col-md-4">

                                        <h5 class="fw-bold mb-1">
                                            {{ $produit['name'] }}
                                        </h5>

                                        <p class="text-muted mb-0">

                                            {{ number_format(
                                                $produit['price'],
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </p>

                                    </div>


                                    {{-- QUANTITÉ --}}
                                    <div class="col-6 col-md-3">

                                        <div class="d-flex align-items-center">

                                            <button
                                                type="button"
                                                wire:click="decrease({{ $id }})"
                                                class="btn btn-outline-dark btn-sm"
                                            >
                                                <i class="bi bi-dash"></i>
                                            </button>


                                            <span class="mx-3 fw-bold">
                                                {{ $produit['quantity'] }}
                                            </span>


                                            <button
                                                type="button"
                                                wire:click="increase({{ $id }})"
                                                class="btn btn-outline-dark btn-sm"
                                            >
                                                <i class="bi bi-plus"></i>
                                            </button>

                                        </div>

                                    </div>


                                    {{-- SOUS-TOTAL --}}
                                    <div class="col-5 col-md-2">

                                        <strong>

                                            {{ number_format(
                                                $produit['price'] * $produit['quantity'],
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </strong>

                                    </div>


                                    {{-- SUPPRIMER --}}
                                    <div class="col-1 text-end">

                                        <button
                                            type="button"
                                            wire:click="remove({{ $id }})"
                                            class="btn btn-link text-danger p-0"
                                            title="Supprimer"
                                        >

                                            <i class="bi bi-trash fs-5"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>


                {{-- ================================= --}}
                {{-- RÉSUMÉ --}}
                {{-- ================================= --}}

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h4 class="fw-bold mb-4">
                                Résumé de la commande
                            </h4>


                            {{-- SOUS-TOTAL --}}
                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Sous-total
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


                            {{-- LIVRAISON --}}
                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Livraison
                                </span>

                                <strong>
                                    À déterminer
                                </strong>

                            </div>


                            <hr>


                            {{-- TOTAL --}}
                            <div class="d-flex justify-content-between mb-4">

                                <span class="fw-bold">
                                    Total
                                </span>

                                <span class="fw-bold fs-5">

                                    {{ number_format(
                                        $this->total,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </span>

                            </div>


                            {{-- COMMANDER --}}
                            <a
                                href="{{ route('commande') }}"
                                class="btn btn-dark w-100 py-3"
                            >

                                <i class="bi bi-credit-card me-2"></i>

                                Commander

                            </a>


                            {{-- CONTINUER LES ACHATS --}}
                            <a
                                href="{{ route('catalogue') }}"
                                class="btn btn-outline-dark w-100 mt-2"
                            >

                                Continuer mes achats

                            </a>

                        </div>

                    </div>

                </div>

            </div>


        {{-- ========================================= --}}
        {{-- PANIER VIDE --}}
        {{-- ========================================= --}}

        @else

            <div class="text-center py-5">

                <div class="mb-4">

                    <i
                        class="bi bi-cart-x"
                        style="font-size:80px;"
                    ></i>

                </div>


                <h3 class="fw-bold">
                    Votre panier est vide
                </h3>


                <p class="text-muted">
                    Vous n'avez encore ajouté aucun produit.
                </p>


                <a
                    href="{{ route('catalogue') }}"
                    class="btn btn-dark px-4 mt-2"
                >

                    <i class="bi bi-shop me-2"></i>

                    Découvrir les produits

                </a>

            </div>

        @endif

    </main>

</div>

