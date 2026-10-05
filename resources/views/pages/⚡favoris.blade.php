<?php

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Mes favoris')] class extends Component
{
    /*
    |--------------------------------------------------------------------------
    | FAVORIS
    |--------------------------------------------------------------------------
    */

    public array $favoris = [];


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->favoris = array_map(
            'intval',
            session()->get('favoris', [])
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRODUITS FAVORIS
    |--------------------------------------------------------------------------
    */

    public function getProduitsFavorisProperty()
    {
        if (empty($this->favoris)) {
            return collect();
        }

        return Product::query()
            ->whereIn('id', $this->favoris)
            ->where('is_active', true)
            ->latest()
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UN FAVORI
    |--------------------------------------------------------------------------
    */

    public function supprimer(int $productId): void
    {
        $this->favoris = array_values(
            array_filter(
                $this->favoris,
                fn ($id) => (int) $id !== $productId
            )
        );

        session()->put('favoris', $this->favoris);

        session()->flash(
            'success',
            'Produit retiré de vos favoris.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VIDER LES FAVORIS
    |--------------------------------------------------------------------------
    */

    public function vider(): void
    {
        $this->favoris = [];

        session()->forget('favoris');

        session()->flash(
            'success',
            'Tous vos favoris ont été supprimés.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJOUTER AU PANIER
    |--------------------------------------------------------------------------
    */

    public function ajouterAuPanier(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product) {
            session()->flash(
                'error',
                'Produit introuvable.'
            );

            return;
        }

        if (!$product->is_active) {
            session()->flash(
                'error',
                'Ce produit n’est plus disponible.'
            );

            return;
        }

        if ($product->stock <= 0) {
            session()->flash(
                'error',
                'Ce produit est en rupture de stock.'
            );

            return;
        }

        $cart = session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | Produit déjà dans le panier
        |--------------------------------------------------------------------------
        */

        if (isset($cart[$productId])) {

            $quantity = (int) (
                $cart[$productId]['quantity'] ?? 1
            );

            if ($quantity >= $product->stock) {

                session()->flash(
                    'error',
                    'La quantité maximale disponible est atteinte.'
                );

                return;
            }

            $cart[$productId]['quantity'] = $quantity + 1;

        } else {

            /*
            |--------------------------------------------------------------------------
            | Nouveau produit
            |--------------------------------------------------------------------------
            */

            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'image' => $product->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        /*
        |--------------------------------------------------------------------------
        | Mise à jour du compteur panier
        |--------------------------------------------------------------------------
        */

        $this->dispatch('cart-updated');

        session()->flash(
            'success',
            $product->name . ' a été ajouté au panier.'
        );
    }
};
?>

<div class="ak-favorites-page">
    @section('title', 'Akatsuki - Favoris')
    {{-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== --}}

    <main class="container py-5">

        {{-- =====================================================
             EN-TÊTE
        ====================================================== --}}

        <div class="favorites-header">

            <div>
                <span class="eyebrow">
                    MA SÉLECTION
                </span>

                <h1>
                    Mes favoris
                    <span>♥</span>
                </h1>

                <p>
                    Retrouvez les produits que vous aimez.
                </p>
            </div>

            @if(count($favoris) > 0)

                <button
                    type="button"
                    wire:click="vider"
                    wire:confirm="Voulez-vous vraiment supprimer tous vos favoris ?"
                    class="favorites-clear-btn"
                >
                    <i class="bi bi-trash"></i>
                    Vider les favoris
                </button>

            @endif

        </div>


        {{-- =====================================================
             MESSAGE SUCCÈS
        ====================================================== --}}

        @if(session()->has('success'))

            <div class="alert alert-success alert-dismissible fade show favorites-alert">
                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>

        @endif


        {{-- =====================================================
             MESSAGE ERREUR
        ====================================================== --}}

        @if(session()->has('error'))

            <div class="alert alert-danger alert-dismissible fade show favorites-alert">
                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>

        @endif


        {{-- =====================================================
             PRODUITS
        ====================================================== --}}

        @if($this->produitsFavoris->count() > 0)

            <div class="row g-4">

                @foreach($this->produitsFavoris as $produit)

                    <div
                        class="col-md-6 col-lg-4 col-xl-3"
                        wire:key="favori-{{ $produit->id }}"
                    >

                        <article class="favorite-card">

                            {{-- IMAGE --}}

                            <div class="favorite-image">

                                @if($produit->image)

                                    <img
                                        src="{{ asset('storage/' . $produit->image) }}"
                                        alt="{{ $produit->name }}"
                                    >

                                @else

                                    <div class="favorite-no-image">
                                        <i class="bi bi-image"></i>
                                    </div>

                                @endif


                                {{-- RETIRER FAVORI --}}

                                <button
                                    type="button"
                                    wire:click="supprimer({{ $produit->id }})"
                                    class="favorite-remove"
                                    title="Retirer des favoris"
                                >
                                    <i class="bi bi-heart-fill"></i>
                                </button>


                                {{-- POPULAIRE --}}

                                @if($produit->is_popular ?? false)

                                    <span class="favorite-popular">
                                        <i class="bi bi-star-fill"></i>
                                        Populaire
                                    </span>

                                @endif

                            </div>


                            {{-- CONTENU --}}

                            <div class="favorite-content">

                                @if($produit->category)

                                    <span class="favorite-category">
                                        {{ $produit->category }}
                                    </span>

                                @endif


                                <h2>
                                    {{ $produit->name }}
                                </h2>


                                {{-- PRIX --}}

                                <div class="favorite-price">

                                    <strong>
                                        {{ number_format(
                                            (float) $produit->price,
                                            0,
                                            ',',
                                            ' '
                                        ) }}
                                        FCFA
                                    </strong>

                                    @if(
                                        isset($produit->old_price) &&
                                        $produit->old_price &&
                                        $produit->old_price > $produit->price
                                    )

                                        <del>
                                            {{ number_format(
                                                (float) $produit->old_price,
                                                0,
                                                ',',
                                                ' '
                                            ) }}
                                            FCFA
                                        </del>

                                    @endif

                                </div>


                                {{-- STOCK --}}

                                @if($produit->stock > 0)

                                    <p class="favorite-stock available">
                                        <i class="bi bi-check-circle"></i>
                                        Disponible
                                    </p>

                                @else

                                    <p class="favorite-stock unavailable">
                                        <i class="bi bi-x-circle"></i>
                                        Rupture de stock
                                    </p>

                                @endif


                                {{-- ACTIONS --}}

                                <div class="favorite-actions">

                                    <a
                                        href="{{ route('produitDetail', $produit->slug) }}"
                                        class="favorite-view-btn"
                                    >
                                        <i class="bi bi-eye"></i>
                                        Voir
                                    </a>


                                    <button
                                        type="button"
                                        wire:click="ajouterAuPanier({{ $produit->id }})"
                                        wire:loading.attr="disabled"
                                        class="favorite-cart-btn"
                                        @disabled($produit->stock <= 0)
                                    >

                                        <span wire:loading.remove wire:target="ajouterAuPanier({{ $produit->id }})">
                                            <i class="bi bi-cart-plus"></i>
                                        </span>

                                        <span wire:loading wire:target="ajouterAuPanier({{ $produit->id }})">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </span>

                                    </button>

                                </div>

                            </div>

                        </article>

                    </div>

                @endforeach

            </div>

        @else

            {{-- =================================================
                 AUCUN FAVORI
            ================================================== --}}

            <div class="favorites-empty">

                <div class="favorites-empty-icon">
                    <i class="bi bi-heart"></i>
                </div>

                <h2>
                    Aucun favori
                </h2>

                <p>
                    Vous n'avez encore ajouté aucun produit
                    à vos favoris.
                </p>

                <a
                    href="{{ route('catalogue') }}"
                    class="favorites-shop-btn"
                >
                    <i class="bi bi-shop"></i>
                    Découvrir les produits
                </a>

            </div>

        @endif

    </main>

</div>