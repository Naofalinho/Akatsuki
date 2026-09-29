<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    /*
    |--------------------------------------------------------------------------
    | FAVORIS
    |--------------------------------------------------------------------------
    |
    | Les IDs des produits favoris sont stockés dans la session.
    |
    */

    public array $favoris = [];


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->favoris = session()->get('favoris', []);
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

        session()->put(
            'favoris',
            $this->favoris
        );

        session()->flash(
            'success',
            'Produit retiré de vos favoris.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VIDER TOUS LES FAVORIS
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


        /*
        | Produit introuvable
        */
        if (!$product) {

            session()->flash(
                'error',
                'Produit introuvable.'
            );

            return;
        }


        /*
        | Produit désactivé
        */
        if (!$product->is_active) {

            session()->flash(
                'error',
                'Ce produit n’est plus disponible.'
            );

            return;
        }


        /*
        | Rupture de stock
        */
        if ($product->stock <= 0) {

            session()->flash(
                'error',
                'Ce produit est en rupture de stock.'
            );

            return;
        }


        /*
        | Récupérer le panier
        */
        $cart = session()->get(
            'cart',
            []
        );


        /*
        | Produit déjà présent
        */
        if (isset($cart[$productId])) {

            $quantity =
                $cart[$productId]['quantity'] ?? 1;


            /*
            | Vérifier le stock
            */
            if ($quantity >= $product->stock) {

                session()->flash(
                    'error',
                    'La quantité maximale disponible est atteinte.'
                );

                return;
            }


            $cart[$productId]['quantity'] =
                $quantity + 1;

        } else {

            /*
            | Ajouter le produit
            */
            $cart[$productId] = [

                'id' => $product->id,

                'name' => $product->name,

                'price' => $product->price,

                'old_price' => $product->old_price,

                'image' => $product->image,

                'slug' => $product->slug,

                'quantity' => 1,

            ];
        }


        /*
        | Sauvegarder
        */
        session()->put(
            'cart',
            $cart
        );


        session()->flash(
            'success',
            $product->name . ' a été ajouté au panier.'
        );
    }
};
?>

<div>

    @section('title', 'Akatsuki - Favoris')


    <main class="container py-5">


        {{-- =====================================================
             TITRE
        ====================================================== --}}

        <div
            class="d-flex justify-content-between align-items-center mb-4"
        >

            <div>

                <span class="eyebrow">
                    MA SÉLECTION
                </span>


                <h1 class="fw-bold mb-1">
                    Mes favoris ❤️
                </h1>


                <p class="text-muted mb-0">
                    Retrouvez les produits que vous aimez.
                </p>

            </div>


            @if(count($favoris) > 0)

                <button
                    type="button"
                    wire:click="vider"
                    wire:confirm="Voulez-vous vraiment supprimer tous vos favoris ?"
                    class="btn btn-outline-danger"
                >

                    <i class="bi bi-trash me-1"></i>

                    Vider les favoris

                </button>

            @endif

        </div>



        {{-- =====================================================
             MESSAGES
        ====================================================== --}}

        @if(session()->has('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @if(session()->has('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

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
             FAVORIS
        ====================================================== --}}

        @if($this->produitsFavoris->count() > 0)


            <div class="row g-4">


                @foreach($this->produitsFavoris as $produit)


                    <div
                        class="col-md-6 col-lg-4 col-xl-3"
                        wire:key="favori-{{ $produit->id }}"
                    >


                        <div class="product-card h-100">


                            {{-- =================================
                                 IMAGE
                            ================================== --}}

                            <div
                                class="product-image position-relative"
                            >


                                @if($produit->image)

                                    <img
                                        src="{{ asset('storage/' . $produit->image) }}"
                                        alt="{{ $produit->name }}"
                                    >

                                @else

                                    <div
                                        class="d-flex align-items-center justify-content-center h-100 bg-light"
                                    >

                                        <i
                                            class="bi bi-image fs-1 text-muted"
                                        ></i>

                                    </div>

                                @endif



                                {{-- BOUTON FAVORI --}}

                                <button
                                    type="button"
                                    wire:click="supprimer({{ $produit->id }})"
                                    class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle"
                                    title="Retirer des favoris"
                                >

                                    ❤️

                                </button>



                                {{-- PRODUIT POPULAIRE --}}

                                @if($produit->is_popular)

                                    <span
                                        class="position-absolute bottom-0 start-0 m-2 badge bg-danger"
                                    >

                                        <i
                                            class="bi bi-star-fill me-1"
                                        ></i>

                                        Populaire

                                    </span>

                                @endif


                            </div>



                            {{-- =================================
                                 CONTENU
                            ================================== --}}

                            <div class="product-content">


                                {{-- CATÉGORIE --}}

                                <span class="product-category">

                                    {{ $produit->category }}

                                </span>



                                {{-- NOM --}}

                                <h3>

                                    {{ $produit->name }}

                                </h3>



                                {{-- PRIX --}}

                                <div class="product-price">


                                    <span class="fw-bold">

                                        {{ number_format(
                                            $produit->price,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </span>



                                    {{-- ANCIEN PRIX --}}

                                    @if(
                                        $produit->old_price &&
                                        $produit->old_price > $produit->price
                                    )

                                        <del
                                            class="text-muted small ms-2"
                                        >

                                            {{ number_format(
                                                $produit->old_price,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </del>

                                    @endif


                                </div>



                                {{-- RÉDUCTION --}}

                                @if($produit->discount_percentage)

                                    <span
                                        class="badge bg-danger mt-2"
                                    >

                                        -{{ $produit->discount_percentage }}%

                                    </span>

                                @endif



                                {{-- STOCK --}}

                                @if($produit->stock > 0)

                                    <p
                                        class="small text-success mt-2 mb-2"
                                    >

                                        <i
                                            class="bi bi-check-circle"
                                        ></i>

                                        Disponible

                                    </p>

                                @else

                                    <p
                                        class="small text-danger mt-2 mb-2"
                                    >

                                        <i
                                            class="bi bi-x-circle"
                                        ></i>

                                        Rupture de stock

                                    </p>

                                @endif



                                {{-- =================================
                                     ACTIONS
                                ================================== --}}

                                <div
                                    class="d-flex gap-2 mt-3"
                                >


                                    {{-- VOIR --}}

                                    <a
                                        href="{{ route(
                                            'produitDetail',
                                            $produit->slug
                                        ) }}"
                                        class="btn btn-outline-dark rounded-pill flex-grow-1"
                                    >

                                        <i
                                            class="bi bi-eye me-1"
                                        ></i>

                                        Voir

                                    </a>



                                    {{-- PANIER --}}

                                    <button
                                        type="button"
                                        wire:click="ajouterAuPanier({{ $produit->id }})"
                                        wire:loading.attr="disabled"
                                        class="btn btn-dark rounded-pill"
                                        @disabled($produit->stock <= 0)
                                    >

                                        <i
                                            class="bi bi-cart-plus"
                                        ></i>

                                    </button>


                                </div>


                            </div>


                        </div>


                    </div>


                @endforeach


            </div>


        @else


            {{-- =================================================
                 AUCUN FAVORI
            ================================================== --}}

            <div class="text-center py-5">


                <div class="mb-4">

                    <i
                        class="bi bi-heart"
                        style="font-size: 80px;"
                    ></i>

                </div>


                <h3 class="fw-bold">
                    Aucun favori
                </h3>


                <p class="text-muted">

                    Vous n'avez encore ajouté aucun
                    produit à vos favoris.

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

