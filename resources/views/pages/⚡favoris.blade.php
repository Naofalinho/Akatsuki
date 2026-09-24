<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>

    @section('title', 'Akatsuki - Favoris')

    <main class="container py-5">

        {{-- TITRE --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

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
                    wire:click="vider"
                    wire:confirm="Voulez-vous vraiment supprimer tous vos favoris ?"
                    class="btn btn-outline-danger"
                >
                    <i class="bi bi-trash me-1"></i>
                    Vider les favoris
                </button>

            @endif

        </div>


        {{-- MESSAGES --}}

        @if(session()->has('success'))

            <div class="alert alert-success alert-dismissible fade show">

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

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- FAVORIS --}}

        @if($this->produitsFavoris->count() > 0)

            <div class="row g-4">

                @foreach($this->produitsFavoris as $produit)

                    <div
                        class="col-md-6 col-lg-4 col-xl-3"
                        wire:key="favori-{{ $produit->id }}"
                    >

                        <div class="product-card h-100">

                            {{-- IMAGE --}}

                            <div class="product-image position-relative">

                                @if($produit->image)

                                    <img
                                        src="{{ asset('storage/' . $produit->image) }}"
                                        alt="{{ $produit->nom }}"
                                    >

                                @else

                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">

                                        <i class="bi bi-image fs-1 text-muted"></i>

                                    </div>

                                @endif


                                {{-- BOUTON FAVORI --}}

                                <button
                                    wire:click="supprimer({{ $produit->id }})"
                                    class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle"
                                    title="Retirer des favoris"
                                >
                                    ❤️
                                </button>

                            </div>


                            {{-- CONTENU --}}

                            <div class="product-content">

                                <span class="product-category">
                                    {{ $produit->categorie }}
                                </span>


                                <h3>
                                    {{ $produit->nom }}
                                </h3>


                                <p class="product-price">

                                    {{ number_format(
                                        $produit->prix,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </p>


                                {{-- STOCK --}}

                                @if($produit->stock > 0)

                                    <p class="small text-success">

                                        <i class="bi bi-check-circle"></i>

                                        Disponible

                                    </p>

                                @else

                                    <p class="small text-danger">

                                        <i class="bi bi-x-circle"></i>

                                        Rupture de stock

                                    </p>

                                @endif


                                {{-- ACTIONS --}}

                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route(
                                            'produit.detail',
                                            $produit->id
                                        ) }}"
                                        class="btn btn-outline-dark rounded-pill flex-grow-1"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Voir
                                    </a>


                                    <button
                                        wire:click="ajouterAuPanier({{ $produit->id }})"
                                        wire:loading.attr="disabled"
                                        class="btn btn-dark rounded-pill"
                                        @disabled($produit->stock <= 0)
                                    >

                                        <i class="bi bi-cart-plus"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- AUCUN FAVORI --}}

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
                    Vous n'avez encore ajouté aucun produit à vos favoris.
                </p>

                <a
                    href="{{ route('produits') }}"
                    class="btn btn-dark px-4 mt-2"
                >
                    <i class="bi bi-shop me-2"></i>
                    Découvrir les produits
                </a>

            </div>

        @endif

    </main>

</div>