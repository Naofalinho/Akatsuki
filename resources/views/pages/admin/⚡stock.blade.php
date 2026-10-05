<?php

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.admin')]
#[Title('Gestion du stock')]
class extends Component
{
    public string $search = '';
    public string $category = '';
    public string $stockStatus = '';

    public function getProductsProperty()
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->stockStatus === 'out', function ($query) {
                $query->where('stock', '<=', 0);
            })
            ->when($this->stockStatus === 'low', function ($query) {
                $query->whereBetween('stock', [1, 5]);
            })
            ->when($this->stockStatus === 'available', function ($query) {
                $query->where('stock', '>', 5);
            })
            ->latest()
            ->get();
    }

    public function getTotalProductsProperty(): int
    {
        return Product::count();
    }

    public function getAvailableProductsProperty(): int
    {
        return Product::where('stock', '>', 0)->count();
    }

    public function getLowStockProductsProperty(): int
    {
        return Product::whereBetween('stock', [1, 5])->count();
    }

    public function getOutOfStockProductsProperty(): int
    {
        return Product::where('stock', '<=', 0)->count();
    }
};
?>

<div class="admin-page">
    @section('title', 'Admin -Stock ')
    {{-- En-tête --}}
    <div class="page-header mb-4">

        <div>
            <div class="page-eyebrow">
                GESTION
            </div>

            <h2 class="page-title">
                Gestion du stock
            </h2>

            <p class="page-subtitle">
                Consultez et surveillez les niveaux de stock de vos produits.
            </p>
        </div>

        <div>
            <a
                href="{{ route('admin.produits.create') }}"
                class="btn btn-ak"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Ajouter un produit
            </a>
        </div>

    </div>


    {{-- Statistiques --}}
    {{-- Statistiques du stock --}}
<div class="row g-4 mb-4">

    {{-- Total produits --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stock-stat-card">

            <div class="stock-stat-icon total">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="stock-stat-content">
                <span class="stock-stat-label">
                    Total produits
                </span>

                <strong class="stock-stat-value">
                    {{ $this->totalProducts }}
                </strong>

                <span class="stock-stat-description">
                    Produits enregistrés
                </span>
            </div>

        </div>
    </div>


    {{-- En stock --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stock-stat-card">

            <div class="stock-stat-icon available">
                <i class="bi bi-check-circle"></i>
            </div>

            <div class="stock-stat-content">
                <span class="stock-stat-label">
                    En stock
                </span>

                <strong class="stock-stat-value">
                    {{ $this->availableProducts }}
                </strong>

                <span class="stock-stat-description">
                    Produits disponibles
                </span>
            </div>

        </div>
    </div>


    {{-- Stock faible --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stock-stat-card">

            <div class="stock-stat-icon low">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="stock-stat-content">
                <span class="stock-stat-label">
                    Stock faible
                </span>

                <strong class="stock-stat-value">
                    {{ $this->lowStockProducts }}
                </strong>

                <span class="stock-stat-description">
                    Entre 1 et 5 unités
                </span>
            </div>

        </div>
    </div>


    {{-- Rupture --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stock-stat-card">

            <div class="stock-stat-icon out">
                <i class="bi bi-x-circle"></i>
            </div>

            <div class="stock-stat-content">
                <span class="stock-stat-label">
                    Rupture
                </span>

                <strong class="stock-stat-value">
                    {{ $this->outOfStockProducts }}
                </strong>

                <span class="stock-stat-description">
                    Produits indisponibles
                </span>
            </div>

        </div>
    </div>

</div>


    {{-- Tableau --}}
    <div class="admin-card">

        {{-- Barre de recherche / filtres --}}
        <div class="card-header-custom">

            <div>
                <h3 class="card-title">
                    État du stock
                </h3>

                <p class="card-subtitle">
                    Suivez les quantités disponibles pour chaque produit.
                </p>
            </div>

        </div>


        <div class="stock-filters p-3 border-bottom">

            <div class="row g-3">

                {{-- Recherche --}}
                <div class="col-lg-5">

                    <label class="form-label">
                        Rechercher
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nom ou référence du produit..."
                            wire:model.live="search"
                        >

                    </div>

                </div>


                {{-- Catégorie --}}
                <div class="col-lg-3">

                    <label class="form-label">
                        Catégorie
                    </label>

                    <select
                        class="form-select"
                        wire:model.live="category"
                    >
                        <option value="">
                            Toutes les catégories
                        </option>

                        <option value="Mode">
                            Mode
                        </option>

                        <option value="Tech">
                            Tech
                        </option>

                        <option value="Maison">
                            Maison
                        </option>

                        <option value="Beauté">
                            Beauté
                        </option>
                    </select>

                </div>


                {{-- État du stock --}}
                <div class="col-lg-4">

                    <label class="form-label">
                        État du stock
                    </label>

                    <select
                        class="form-select"
                        wire:model.live="stockStatus"
                    >
                        <option value="">
                            Tous les stocks
                        </option>

                        <option value="available">
                            Disponible
                        </option>

                        <option value="low">
                            Stock faible
                        </option>

                        <option value="out">
                            Rupture de stock
                        </option>
                    </select>

                </div>

            </div>

        </div>


        {{-- Tableau --}}
        <div class="table-responsive">

            <table class="table admin-table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>État</th>
                        <th>Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($this->products as $product)

                        <tr>

                            {{-- Produit --}}
                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div
                                        class="product-thumb"
                                        style="width: 52px; height: 52px;"
                                    >

                                        @if($product->image)

                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                style="
                                                    width: 100%;
                                                    height: 100%;
                                                    object-fit: cover;
                                                    border-radius: 8px;
                                                "
                                            >

                                        @else

                                            <div
                                                class="d-flex align-items-center justify-content-center bg-light"
                                                style="
                                                    width: 100%;
                                                    height: 100%;
                                                    border-radius: 8px;
                                                "
                                            >
                                                <i class="bi bi-image text-muted"></i>
                                            </div>

                                        @endif

                                    </div>

                                    <div>

                                        <strong class="d-block">
                                            {{ $product->name }}
                                        </strong>

                                        <small class="text-muted">
                                            {{ $product->slug }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Catégorie --}}
                            <td>
                                <span class="category-badge">
                                    {{ $product->category }}
                                </span>
                            </td>


                            {{-- Prix --}}
                            <td>
                                <strong>
                                    {{ number_format($product->price, 0, ',', ' ') }}
                                    FCFA
                                </strong>
                            </td>


                            {{-- Stock --}}
                            <td>

                                <strong
                                    class="
                                        stock-quantity
                                        @if($product->stock <= 0)
                                            text-danger
                                        @elseif($product->stock <= 5)
                                            text-warning
                                        @else
                                            text-success
                                        @endif
                                    "
                                >
                                    {{ $product->stock }}
                                </strong>

                                <small class="text-muted">
                                    unité(s)
                                </small>

                            </td>


                            {{-- État --}}
                            <td>

                                @if($product->stock <= 0)

                                    <span class="order-status order-cancelled">
                                        <i class="bi bi-x-circle"></i>
                                        Rupture

                                    </span>

                                @elseif($product->stock <= 5)

                                    <span class="order-status order-pending">
                                        <i class="bi bi-exclamation-circle"></i>
                                        Stock faible
                                    </span>

                                @else

                                    <span class="order-status order-delivered">
                                        <i class="bi bi-check-circle"></i>
                                        Disponible
                                    </span>

                                @endif

                            </td>


                            {{-- Statut produit --}}
                            <td>

                                @if($product->is_active)

                                    <span class="order-status order-delivered">
                                        Actif
                                    </span>

                                @else

                                    <span class="order-status order-cancelled">
                                        Inactif
                                    </span>

                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="text-end">

                                <a
                                    href="{{ route('admin.produits.edit', $product->id) }}"
                                    class="btn btn-sm btn-outline-ak"
                                    title="Modifier le produit"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a
                                    href="{{ route('admin.produits.show', $product->id) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="Voir le produit"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="empty-state">

                                    <i class="bi bi-box-seam"></i>

                                    <h5>
                                        Aucun produit trouvé
                                    </h5>

                                    <p>
                                        Aucun produit ne correspond aux filtres sélectionnés.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>