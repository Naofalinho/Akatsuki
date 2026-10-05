<?php

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.admin')] class extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }
};
?>

<div class="admin-content">
@section('title', 'Admin -Voir le produit ')
    {{-- En-tête --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <a
                    href="{{ route('admin.produits') }}"
                    class="text-decoration-none text-muted"
                >
                    <i class="bi bi-arrow-left"></i>
                    Produits
                </a>

                <span class="text-muted">/</span>

                <span class="text-muted">
                    Détails
                </span>

            </div>

            <h2 class="page-title mb-1">
                {{ $product->name }}
            </h2>

            <p class="text-muted mb-0">
                Informations détaillées du produit.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.produits.edit', $product->id) }}"
                class="btn btn-ak"
            >
                <i class="bi bi-pencil me-2"></i>
                Modifier
            </a>

            <a
                href="{{ route('admin.produits') }}"
                class="btn btn-outline-secondary"
            >
                Retour
            </a>

        </div>

    </div>


    <div class="row g-4">

        {{-- Image --}}
        <div class="col-lg-5">

            <div class="card-admin h-100">

                <div class="product-detail-image">

                    @if ($product->image)

                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}" 
                        >

                    @else

                        <div class="product-no-image">

                            <i class="bi bi-image"></i>

                            <span>
                                Aucune image
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Informations --}}
        <div class="col-lg-7">

            <div class="card-admin h-100">

                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div>

                        <span class="text-muted small">
                            PRODUIT
                        </span>

                        <h4 class="mb-1">
                            {{ $product->name }}
                        </h4>

                        <small class="text-muted">
                            {{ $product->slug }}
                        </small>

                    </div>


                    @if ($product->is_active)

                        <span class="status-active">
                            Actif
                        </span>

                    @else

                        <span class="status-inactive">
                            Inactif
                        </span>

                    @endif

                </div>


                {{-- Prix --}}
                <div class="product-detail-price mb-4">

                    <span class="text-muted">
                        Prix
                    </span>

                    <div>

                        <strong>
                            {{ number_format($product->price, 0, ',', ' ') }}
                            FCFA
                        </strong>

                        @if ($product->old_price)

                            <span class="old-price">

                                {{ number_format($product->old_price, 0, ',', ' ') }}
                                FCFA

                            </span>

                            @if ($product->discount_percentage)

                                <span class="discount-badge">
                                    -{{ $product->discount_percentage }}%
                                </span>

                            @endif

                        @endif

                    </div>

                </div>


                {{-- Informations --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-6">

                        <div class="product-info-box">

                            <span>
                                Catégorie
                            </span>

                            <strong>
                                {{ $product->category }}
                            </strong>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="product-info-box">

                            <span>
                                Stock
                            </span>

                            <strong>

                                @if ($product->stock > 10)

                                    <span class="stock-ok">
                                        {{ $product->stock }} disponibles
                                    </span>

                                @elseif ($product->stock > 0)

                                    <span class="stock-warning">
                                        {{ $product->stock }} disponibles
                                    </span>

                                @else

                                    <span class="stock-danger">
                                        Rupture de stock
                                    </span>

                                @endif

                            </strong>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="product-info-box">

                            <span>
                                Produit populaire
                            </span>

                            <strong>

                                @if ($product->is_popular)

                                    <span class="text-success">
                                        <i class="bi bi-star-fill me-1"></i>
                                        Oui
                                    </span>

                                @else

                                    <span class="text-muted">
                                        Non
                                    </span>

                                @endif

                            </strong>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="product-info-box">

                            <span>
                                Ajouté le
                            </span>

                            <strong>
                                {{ $product->created_at?->format('d/m/Y à H:i') }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Description --}}
                <div>

                    <h6 class="fw-bold mb-2">
                        Description
                    </h6>

                    <div class="product-description">

                        @if ($product->description)

                            {!! nl2br(e($product->description)) !!}

                        @else

                            <span class="text-muted">
                                Aucune description disponible.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>