<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    /*
    |--------------------------------------------------------------------------
    | AJOUTER AUX FAVORIS
    |--------------------------------------------------------------------------
    */
    public function ajouterAuxFavoris(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product) {
            session()->flash('error', 'Produit introuvable.');
            return;
        }

        if (!$product->is_active) {
            session()->flash(
                'error',
                'Ce produit n’est plus disponible.'
            );

            return;
        }

        $favoris = session()->get('favoris', []);

        if (in_array($productId, $favoris)) {
            session()->flash(
                'error',
                'Ce produit est déjà dans vos favoris.'
            );

            return;
        }

        $favoris[] = $productId;

        session()->put('favoris', $favoris);

        $this->dispatch('favoris-updated');

        session()->flash(
            'success',
            $product->name . ' a été ajouté à vos favoris.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJOUTER AU PANIER
    |--------------------------------------------------------------------------
    */
    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product) {
            $this->dispatch(
                'cart-error',
                message: 'Produit introuvable.'
            );

            return;
        }

        if (!$product->is_active || $product->stock <= 0) {
            $this->dispatch(
                'cart-error',
                message: 'Ce produit est actuellement indisponible.'
            );

            return;
        }

        $cart = session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | PRODUIT DÉJÀ DANS LE PANIER
        |--------------------------------------------------------------------------
        */
        if (isset($cart[$productId])) {

            if ($cart[$productId]['quantity'] >= $product->stock) {

                $this->dispatch(
                    'cart-error',
                    message: 'Vous avez atteint la quantité disponible en stock.'
                );

                return;
            }

            $cart[$productId]['quantity']++;

        } else {

            /*
            |--------------------------------------------------------------------------
            | NOUVEAU PRODUIT
            |--------------------------------------------------------------------------
            */
            $cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'slug'     => $product->slug,
                'price'    => (float) $product->price,
                'image'    => $product->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch(
            'cart-updated',
            message: $product->name . ' ajouté au panier.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AFFICHAGE
    |--------------------------------------------------------------------------
    */
    public function render()
    {
        $popularProducts = Product::query()
            ->where('is_active', true)
            ->where('is_popular', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('pages.⚡index', [
            'popularProducts' => $popularProducts,
        ])->layout('layouts.base');
    }
};
?>

<div>

    @section('title', 'Akatsuki - Accueil')


    {{-- =========================================================
        HERO
    ========================================================== --}}

    <header class="hero">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <span class="eyebrow">
                        NOUVELLE COLLECTION
                    </span>

                    <h1>
                        Le style qui
                        <span>vous ressemble.</span>
                    </h1>

                    <p class="lead text-secondary">
                        Mode, technologie, maison et beauté réunis
                        dans une boutique moderne.
                    </p>

                    <a
                        href="{{ route('catalogue') }}"
                        class="btn btn-dark btn-lg rounded-pill px-4"
                    >
                        Découvrir la boutique
                    </a>

                </div>


                <div class="col-lg-6">

                    <div class="hero-card">

                        <img
                            src="{{ asset('assets/img/barn.png') }}"
                            alt="Nouvelle collection AKATSUKI"
                        >

                        <div class="hero-content">

                            <span>
                                AKATSUKI
                            </span>

                            <h2>
                                NEW<br>
                                DROP
                            </h2>

                            <p>
                                Jusqu'à -30%
                            </p>

                            <a
                                href="{{ route('catalogue') }}"
                                class="btn btn-light rounded-pill"
                            >
                                Acheter
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </header>


    {{-- =========================================================
        CATÉGORIES
    ========================================================== --}}

    <section class="categories-section py-5">

        <div class="container">

            <div class="section-head">

                <div>
                    <span class="section-eyebrow">
                        EXPLOREZ
                    </span>

                    <h2>
                        Catégories
                    </h2>
                </div>

                <a href="{{ route('catalogue') }}">
                    Tout voir →
                </a>

            </div>


            <div class="row g-4">

                {{-- MODE --}}
                <div class="col-6 col-lg-3">

                    <a
                        href="{{ route('catalogue') }}?cat=Mode"
                        class="category-card c1"
                    >

                        <div class="category-content">

                            <b>
                                Mode
                            </b>

                            <small>
                                Vêtements & accessoires
                            </small>

                        </div>

                    </a>

                </div>


                {{-- TECH --}}
                <div class="col-6 col-lg-3">

                    <a
                        href="{{ route('catalogue') }}?cat=Tech"
                        class="category-card c2"
                    >

                        <div class="category-content">

                            <b>
                                Tech
                            </b>

                            <small>
                                Gadgets & accessoires
                            </small>

                        </div>

                    </a>

                </div>


                {{-- MAISON --}}
                <div class="col-6 col-lg-3">

                    <a
                        href="{{ route('catalogue') }}?cat=Maison"
                        class="category-card c3"
                    >

                        <div class="category-content">

                            <b>
                                Maison
                            </b>

                            <small>
                                Confort & déco
                            </small>

                        </div>

                    </a>

                </div>


                {{-- BEAUTÉ --}}
                <div class="col-6 col-lg-3">

                    <a
                        href="{{ route('catalogue') }}?cat=Beauté"
                        class="category-card c4"
                    >

                        <div class="category-content">

                            <b>
                                Beauté
                            </b>

                            <small>
                                Soins & bien-être
                            </small>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        PRODUITS POPULAIRES
    ========================================================== --}}

    <section class="popular-products-section py-5">

        <div class="container">

            <div class="section-head">

                <div>

                    <span class="section-eyebrow">
                        NOTRE SÉLECTION
                    </span>

                    <h2>
                        Produits populaires
                    </h2>

                </div>

                <a href="{{ route('catalogue') }}">
                    Voir tout →
                </a>

            </div>


            <div class="popular-products-grid">

                @forelse($popularProducts as $product)

                    <article
                        class="product-card"
                        wire:key="popular-product-{{ $product->id }}"
                    >

                        {{-- =================================================
                            IMAGE
                        ================================================== --}}

                        <div class="product-imagee">

                            {{-- BADGE RÉDUCTION --}}
                            @if($product->discount_percentage)

                                <span class="product-badge">
                                    -{{ $product->discount_percentage }}%
                                </span>

                            @elseif($product->old_price && $product->old_price > $product->price)

                                @php
                                    $discount = round(
                                        (($product->old_price - $product->price)
                                        / $product->old_price) * 100
                                    );
                                @endphp

                                <span class="product-badge">
                                    -{{ $discount }}%
                                </span>

                            @endif


                            {{-- FAVORIS --}}
                            <button
                                type="button"
                                wire:click="ajouterAuxFavoris({{ $product->id }})"
                                class="product-favorite"
                                title="Ajouter aux favoris"
                                aria-label="Ajouter {{ $product->name }} aux favoris"
                            >
                                <i class="bi bi-heart"></i>
                            </button>


                            {{-- LIEN IMAGE --}}
                            <a
                                href="{{ route('produitDetail', ['slug' => $product->slug]) }}"
                                class="product-imagee-link"
                            >

                                @if($product->image)

                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="{{ asset('assets/img/product-placeholder.jpg') }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                    >

                                @endif

                            </a>

                        </div>


                        {{-- =================================================
                            INFORMATIONS
                        ================================================== --}}

                        <div class="product-info">

                            <span class="product-category">
                                {{ $product->category }}
                            </span>


                            <h3 class="product-name">

                                <a
                                    href="{{ route('produitDetail', ['slug' => $product->slug]) }}"
                                >
                                    {{ $product->name }}
                                </a>

                            </h3>


                            {{-- PRIX --}}
                            <div class="product-prices">

                                <span class="product-price">
                                    {{ number_format($product->price, 0, ',', ' ') }}
                                    FCFA
                                </span>

                                @if($product->old_price)

                                    <span class="product-old-price">
                                        {{ number_format($product->old_price, 0, ',', ' ') }}
                                        FCFA
                                    </span>

                                @endif

                            </div>


                            {{-- ACTIONS --}}
                            <div class="product-actions">

                                {{-- VOIR --}}
                                <a
                                    href="{{ route('produitDetail', ['slug' => $product->slug]) }}"
                                    class="product-view-btn"
                                >
                                    <i class="bi bi-eye"></i>
                                    Voir
                                </a>


                                {{-- AJOUTER AU PANIER --}}
                                @if($product->is_active && $product->stock > 0)

                                    <button
                                        type="button"
                                        class="product-cart-btn"
                                        wire:click="addToCart({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="addToCart({{ $product->id }})"
                                    >

                                        <span
                                            wire:loading.remove
                                            wire:target="addToCart({{ $product->id }})"
                                        >
                                            <i class="bi bi-cart-plus"></i>
                                            Ajouter
                                        </span>

                                        <span
                                            wire:loading
                                            wire:target="addToCart({{ $product->id }})"
                                        >
                                            <span class="spinner-border spinner-border-sm"></span>
                                            Ajout...
                                        </span>

                                    </button>

                                @else

                                    <button
                                        type="button"
                                        class="product-cart-btn product-cart-disabled"
                                        disabled
                                    >
                                        Rupture
                                    </button>

                                @endif

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="products-empty">

                        <div class="products-empty-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <h3>
                            Aucun produit populaire
                        </h3>

                        <p>
                            Les produits populaires apparaîtront ici.
                        </p>

                        <a
                            href="{{ route('catalogue') }}"
                            class="btn btn-dark rounded-pill px-4"
                        >
                            Voir le catalogue
                        </a>

                    </div>

                @endforelse

            </div>

        </div>

    </section>

</div>

