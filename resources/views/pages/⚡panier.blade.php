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
        $cart = session()->get('cart', []);

        if (!is_array($cart)) {
            $cart = [];
        }

        $this->cart = $cart;
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
            unset($this->cart[$productId]);
            $this->saveCart();

            return;
        }

        $quantity = (int) ($this->cart[$productId]['quantity'] ?? 0);

        if ($quantity >= $product->stock) {
            return;
        }

        $this->cart[$productId]['quantity'] = $quantity + 1;

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

        $quantity = (int) ($this->cart[$productId]['quantity'] ?? 1);

        if ($quantity > 1) {
            $this->cart[$productId]['quantity'] = $quantity - 1;
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
     * Vider complètement le panier
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
        return (int) collect($this->cart)->sum(function ($item) {
            return (int) ($item['quantity'] ?? 0);
        });
    }

    /**
     * Total du panier
     */
    public function getTotalProperty(): float
    {
        return (float) collect($this->cart)->sum(function ($item) {
            $price = (float) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);

            return $price * $quantity;
        });
    }
};
?>

<div class="ak-cart-page">

    @section('title', 'Akatsuki - Panier')
    <main class="container py-5">

        {{-- ================================
             EN-TÊTE
        ================================= --}}

        <div class="cart-header">

            <div>
                <span class="cart-eyebrow">
                    AKATSUKI
                </span>

                <h1 class="cart-title">
                    Mon panier
                </h1>

                <p class="cart-subtitle">
                    {{ $this->nombreArticles }}
                    article{{ $this->nombreArticles > 1 ? 's' : '' }}
                    dans votre panier
                </p>
            </div>

            @if(count($cart) > 0)

                <button
                    type="button"
                    wire:click="clearCart"
                    wire:confirm="Voulez-vous vraiment vider votre panier ?"
                    class="cart-clear-btn"
                >
                    <i class="bi bi-trash"></i>
                    Vider le panier
                </button>

            @endif

        </div>


        {{-- ================================
             PANIER NON VIDE
        ================================= --}}

        @if(count($cart) > 0)

            <div class="row g-4">

                {{-- ============================
                     LISTE DES PRODUITS
                ============================= --}}

                <div class="col-lg-8">

                    <div class="cart-products">

                        @foreach($cart as $id => $produit)

                            @php
                                $quantity = (int) ($produit['quantity'] ?? 1);
                                $price = (float) ($produit['price'] ?? 0);
                                $subtotal = $price * $quantity;
                            @endphp

                            <article
                                wire:key="cart-product-{{ $id }}"
                                class="cart-product"
                            >

                                {{-- IMAGE --}}

                                <div class="cart-product-image">

                                    @if(!empty($produit['image']))

                                        <img
                                            src="{{ asset('storage/' . $produit['image']) }}"
                                            alt="{{ $produit['name'] ?? 'Produit' }}"
                                        >

                                    @else

                                        <div class="cart-product-no-image">
                                            <i class="bi bi-image"></i>
                                        </div>

                                    @endif

                                </div>


                                {{-- INFORMATIONS --}}

                                <div class="cart-product-info">

                                    <h2 class="cart-product-name">
                                        {{ $produit['name'] ?? 'Produit' }}
                                    </h2>

                                    @if(!empty($produit['slug']))

                                        <span class="cart-product-slug">
                                            {{ $produit['slug'] }}
                                        </span>

                                    @endif

                                    <div class="cart-product-price">
                                        {{ number_format($price, 0, ',', ' ') }}
                                        FCFA
                                    </div>

                                </div>


                                {{-- QUANTITÉ --}}

                                <div class="cart-product-quantity">

                                    <span class="quantity-label">
                                        Quantité
                                    </span>

                                    <div class="quantity-control">

                                        <button
                                            type="button"
                                            wire:click="decrease({{ $id }})"
                                            class="quantity-btn"
                                            title="Diminuer"
                                        >
                                            <i class="bi bi-dash"></i>
                                        </button>

                                        <span class="quantity-value">
                                            {{ $quantity }}
                                        </span>

                                        <button
                                            type="button"
                                            wire:click="increase({{ $id }})"
                                            class="quantity-btn"
                                            title="Augmenter"
                                        >
                                            <i class="bi bi-plus"></i>
                                        </button>

                                    </div>

                                </div>


                                {{-- SOUS-TOTAL --}}

                                <div class="cart-product-total">

                                    <span class="total-label">
                                        Sous-total
                                    </span>

                                    <strong>
                                        {{ number_format($subtotal, 0, ',', ' ') }}
                                        FCFA
                                    </strong>

                                </div>


                                {{-- SUPPRIMER --}}

                                <button
                                    type="button"
                                    wire:click="remove({{ $id }})"
                                    class="cart-remove-btn"
                                    title="Supprimer le produit"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </article>

                        @endforeach

                    </div>

                </div>


                {{-- ============================
                     RÉSUMÉ
                ============================= --}}

                <div class="col-lg-4">

                    <aside class="cart-summary">

                        <div class="cart-summary-header">

                            <span class="cart-summary-eyebrow">
                                COMMANDE
                            </span>

                            <h2>
                                Résumé de la commande
                            </h2>

                        </div>


                        <div class="cart-summary-body">

                            {{-- SOUS-TOTAL --}}

                            <div class="summary-line">

                                <span>
                                    Sous-total
                                </span>

                                <strong>
                                    {{ number_format($this->total, 0, ',', ' ') }}
                                    FCFA
                                </strong>

                            </div>


                            {{-- LIVRAISON --}}

                            <div class="summary-line">

                                <span>
                                    Livraison
                                </span>

                                <strong class="summary-muted">
                                    À déterminer
                                </strong>

                            </div>


                            <div class="summary-divider"></div>


                            {{-- TOTAL --}}

                            <div class="summary-total">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    {{ number_format($this->total, 0, ',', ' ') }}
                                    FCFA
                                </strong>

                            </div>


                            {{-- COMMANDER --}}

                            <a
                                href="{{ route('commande') }}"
                                class="cart-checkout-btn"
                            >
                                <i class="bi bi-bag-check"></i>
                                Passer la commande
                            </a>


                            {{-- CONTINUER LES ACHATS --}}

                            <a
                                href="{{ route('catalogue') }}"
                                class="cart-continue-btn"
                            >
                                <i class="bi bi-arrow-left"></i>
                                Continuer mes achats
                            </a>

                        </div>

                    </aside>

                </div>

            </div>


        {{-- ================================
             PANIER VIDE
        ================================= --}}

        @else

            <section class="cart-empty">

                <div class="cart-empty-icon">
                    <i class="bi bi-cart-x"></i>
                </div>

                <span class="cart-empty-eyebrow">
                    PANIER VIDE
                </span>

                <h2>
                    Votre panier est vide
                </h2>

                <p>
                    Vous n'avez encore ajouté aucun produit à votre panier.
                </p>

                <a
                    href="{{ route('catalogue') }}"
                    class="cart-empty-btn"
                >
                    <i class="bi bi-shop"></i>
                    Découvrir les produits
                </a>

            </section>

        @endif

    </main>

</div>