<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
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
    /**
     * Ajouter un produit au panier
     */
    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        // Produit inexistant
        if (!$product) {
            return;
        }

        // Produit indisponible
        if (!$product->is_active || $product->stock <= 0) {
            $this->dispatch(
                'cart-error',
                message: 'Ce produit est actuellement indisponible.'
            );

            return;
        }

        // Récupération du panier
        $cart = session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | Produit déjà présent
        |--------------------------------------------------------------------------
        */
        if (isset($cart[$productId])) {

            // Vérifier la limite du stock
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
            | Nouveau produit
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

        /*
        |--------------------------------------------------------------------------
        | Sauvegarder le panier
        |--------------------------------------------------------------------------
        */
        session()->put('cart', $cart);

        /*
        |--------------------------------------------------------------------------
        | Mise à jour du compteur du panier
        |--------------------------------------------------------------------------
        */
        $this->dispatch(
            'cart-updated',
            message: $product->name . ' ajouté au panier.'
        );
    }


    /**
     * Affichage de la page
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

    @section('title', __('Akatsuki - Accueil'))


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

                    <a class="btn btn-dark btn-lg rounded-pill px-4" href="{{ route('catalogue') }}">
                        Découvrir la boutique
                    </a>

                </div>


                <div class="col-lg-6">

                    <div class="hero-card">

                        <img src="{{ asset('assets/img/barn.png') }}" alt="Nouvelle collection AKATSUKI">

                        <div>

                            <span>AKATSUKI</span>

                            <h2>
                                NEW<br>
                                DROP
                            </h2>

                            <p>
                                Jusqu'à -30%
                            </p>

                            <a href="{{ route('catalogue') }}" class="btn btn-light rounded-pill">
                                Acheter
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </header>


    {{-- =========================================================
    CATEGORIES
    ========================================================== --}}

    <section class="py-5">

        <div class="container">

            <div class="section-head">

                <h2>
                    Catégories
                </h2>

                <a href="{{ route('catalogue') }}">
                    Tout voir →
                </a>

            </div>


            <div class="row g-3">

                {{-- MODE --}}
                <div class="col-6 col-lg-3">

                    <a class="category-card c1" href="{{ route('catalogue') }}?cat=Mode">
                        <b>
                            Mode
                        </b>

                        <small>
                            Vêtements & accessoires
                        </small>
                    </a>

                </div>


                {{-- TECH --}}
                <div class="col-6 col-lg-3">

                    <a class="category-card c2" href="{{ route('catalogue') }}?cat=Tech">
                        <b>
                            Tech
                        </b>

                        <small>
                            Gadgets & accessoires
                        </small>
                    </a>

                </div>


                {{-- MAISON --}}
                <div class="col-6 col-lg-3">

                    <a class="category-card c3" href="{{ route('catalogue') }}?cat=Maison">
                        <b>
                            Maison
                        </b>

                        <small>
                            Confort & déco
                        </small>
                    </a>

                </div>


                {{-- BEAUTE --}}
                <div class="col-6 col-lg-3">

                    <a class="category-card c4" href="{{ route('catalogue') }}?cat=Beauté">
                        <b>
                            Beauté
                        </b>

                        <small>
                            Soins & bien-être
                        </small>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
    PRODUITS POPULAIRES
    ========================================================== --}}

    <section class="py-5 bg-light">

        <div class="container">

            <div class="section-head">

                <h2>
                    Produits populaires
                </h2>

                <a href="{{ route('catalogue') }}">
                    Voir tout →
                </a>

            </div>


            <div class="row g-4">

                @forelse ($popularProducts as $product)

                <div class="col-6 col-md-4 col-lg-3">

                    <div class="product-card h-100">


                        {{-- =================================================
                        IMAGE
                        ================================================== --}}

                        <div class="product-image">


                            {{-- REDUCTION --}}
                            @if ($product->discount_percentage)

                            <span class="product-badge">
                                -{{ $product->discount_percentage }}%
                            </span>

                            @endif


                            {{-- IMAGE PRODUIT --}}
                            <a href="{{ route('produitDetail', ['slug' => $product->slug]) }}">

                                @if ($product->image)

                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    loading="lazy">

                                @else

                                <img src="{{ asset('assets/img/product-placeholder.jpg') }}" alt="{{ $product->name }}"
                                    loading="lazy">

                                @endif

                            </a>


                            <!-- Favoris -->
                            <button type="button" wire:click="ajouterAuxFavoris({{ $product->id }})"
                                class="btn btn-light rounded-circle position-absolute top-0 end-0 m-2"
                                title="Ajouter aux favoris">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>


                        {{-- =================================================
                        INFORMATIONS
                        ================================================== --}}

                        <div class="product-info">


                            {{-- CATEGORIE --}}
                            <small class="product-category">
                                {{ $product->category }}
                            </small>


                            {{-- NOM --}}
                            <h3 class="product-name">

                                <a href="{{ route('produitDetail', ['slug' => $product->slug]) }}">
                                    {{ $product->name }}
                                </a>

                            </h3>


                            {{-- PRIX --}}
                            <div class="product-prices">

                                <span class="product-price">

                                    {{ number_format($product->price, 0, ',', ' ') }}
                                    FCFA

                                </span>


                                @if ($product->old_price)

                                <span class="product-old-price">

                                    {{ number_format($product->old_price, 0, ',', ' ') }}
                                    FCFA

                                </span>

                                @endif

                            </div>


                            {{-- =================================================
                            ACTIONS
                            ================================================== --}}

                            <div class="product-actions d-flex gap-2 mt-3">


                                {{-- VOIR --}}
                                <a href="{{ route('produitDetail', ['slug' => $product->slug]) }}"
                                    class="btn btn-outline-dark rounded-pill flex-fill">
                                    <i class="bi bi-eye me-1"></i>
                                    Voir
                                </a>


                                {{-- AJOUTER --}}
                                @if ($product->is_available)

                                <button type="button" class="btn btn-dark rounded-pill flex-fill"
                                    wire:click="addToCart({{ $product->id }})" wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $product->id }})">

                                    {{-- ETAT NORMAL --}}
                                    <span wire:loading.remove wire:target="addToCart({{ $product->id }})">

                                        <i class="bi bi-cart-plus me-1"></i>

                                        Ajouter

                                    </span>


                                    {{-- ETAT CHARGEMENT --}}
                                    <span wire:loading wire:target="addToCart({{ $product->id }})">

                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>

                                        Ajout...

                                    </span>

                                </button>

                                @else

                                {{-- RUPTURE --}}
                                <button type="button" class="btn btn-secondary rounded-pill flex-fill" disabled>
                                    Rupture
                                </button>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                @empty

                {{-- =================================================
                AUCUN PRODUIT
                ================================================== --}}

                <div class="col-12">

                    <div class="text-center py-5">

                        <i class="bi bi-box-seam fs-1 text-secondary"></i>

                        <h3 class="mt-3">
                            Aucun produit populaire
                        </h3>

                        <p class="text-secondary">
                            Les produits populaires apparaîtront ici.
                        </p>

                        <a href="{{ route('catalogue') }}" class="btn btn-dark rounded-pill px-4">
                            Voir le catalogue
                        </a>

                    </div>

                </div>

                @endforelse

            </div>

        </div>

    </section>

</div>