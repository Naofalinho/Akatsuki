<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    @section('title', 'Akatsuki - Produits')

    <main class="container py-5">

        {{-- Messages --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <div class="row">

            {{-- FILTRES --}}
            <div class="col-lg-3 mb-4">

                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-funnel me-2"></i>
                            Filtres
                        </h5>

                        {{-- Recherche --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Rechercher
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Rechercher..."
                                    wire:model.live.debounce.300ms="recherche"
                                >

                            </div>

                        </div>

                        {{-- Catégorie --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Catégorie
                            </label>

                            <select
                                class="form-select"
                                wire:model.live="categorie"
                            >

                                <option value="">
                                    Toutes les catégories
                                </option>

                                <option value="Électronique">
                                    Électronique
                                </option>

                                <option value="Mode">
                                    Mode
                                </option>

                                <option value="Maison">
                                    Maison
                                </option>

                                <option value="Informatique">
                                    Informatique
                                </option>

                                <option value="Accessoires">
                                    Accessoires
                                </option>

                            </select>

                        </div>

                        {{-- Prix --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Prix maximum
                            </label>

                            <input
                                type="range"
                                class="form-range"
                                min="0"
                                max="500000"
                                step="5000"
                                wire:model.live="prixMax"
                            >

                            <div class="d-flex justify-content-between">
                                <small>0 FCFA</small>

                                <strong>
                                    {{ number_format($prixMax, 0, ',', ' ') }}
                                    FCFA
                                </strong>
                            </div>

                        </div>

                        {{-- Tri --}}
                        <div>

                            <label class="form-label fw-semibold">
                                Trier par
                            </label>

                            <select
                                class="form-select"
                                wire:model.live="tri"
                            >

                                <option value="">
                                    Plus récents
                                </option>

                                <option value="asc">
                                    Prix croissant
                                </option>

                                <option value="desc">
                                    Prix décroissant
                                </option>

                                <option value="nom">
                                    Nom A-Z
                                </option>

                            </select>

                        </div>

                    </div>
                </div>

            </div>

            {{-- PRODUITS --}}
            <div class="col-lg-9">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h1 class="fw-bold mb-1">
                            Nos produits
                        </h1>

                        <p class="text-muted mb-0">
                            Découvrez notre catalogue
                        </p>
                    </div>

                    <span class="badge bg-dark rounded-pill">
                        {{ $products->count() }} produit(s)
                    </span>

                </div>

                @if ($products->count() > 0)

                    <div class="row g-4">

                        @foreach ($products as $product)

                            <div
                                class="col-md-6 col-xl-4"
                                wire:key="produit-{{ $product->id }}"
                            >

                                <div class="card h-100 border-0 shadow-sm">

                                    {{-- IMAGE --}}
                                    <div class="position-relative">

                                        @if ($product->image)

                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                class="card-img-top"
                                                alt="{{ $product->nom }}"
                                                style="height:220px; object-fit:cover;"
                                            >

                                        @else

                                            <div
                                                class="bg-light d-flex align-items-center justify-content-center"
                                                style="height:220px;"
                                            >
                                                <i class="bi bi-image fs-1 text-muted"></i>
                                            </div>

                                        @endif

                                        {{-- Stock --}}
                                        @if ($product->stock > 0)

                                            <span class="position-absolute top-0 end-0 m-2 badge bg-success">
                                                En stock
                                            </span>

                                        @else

                                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                                                Rupture
                                            </span>

                                        @endif

                                    </div>

                                    <div class="card-body">

                                        <small class="text-danger fw-semibold">
                                            {{ $product->categorie }}
                                        </small>

                                        <h5 class="fw-bold mt-2">
                                            {{ $product->nom }}
                                        </h5>

                                        <p class="text-muted small">
                                            Réf. {{ $product->reference }}
                                        </p>

                                        <h5 class="fw-bold">
                                            {{ number_format($product->prix, 0, ',', ' ') }}
                                            FCFA
                                        </h5>

                                    </div>

                                    <div class="card-footer bg-white border-0 pb-3">

                                        <div class="d-flex gap-2">

                                            <a
                                                href="{{ route('produit.detail', $product->id) }}"
                                                class="btn btn-outline-dark rounded-pill flex-grow-1"
                                            >
                                                <i class="bi bi-eye me-1"></i>
                                                Voir
                                            </a>

                                            <button
                                                wire:click="ajouterAuPanier({{ $product->id }})"
                                                wire:loading.attr="disabled"
                                                class="btn btn-dark rounded-pill"
                                                @disabled($product->stock <= 0)
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

                    <div class="text-center py-5">

                        <i class="bi bi-search fs-1 text-muted"></i>

                        <h4 class="mt-3">
                            Aucun produit trouvé
                        </h4>

                        <p class="text-muted">
                            Essayez de modifier vos critères de recherche.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </main>
</div>