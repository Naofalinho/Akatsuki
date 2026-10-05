<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';

    /**
     * Revenir à la première page lorsque la recherche change.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Revenir à la première page lorsque le filtre change.
     */
    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Liste des produits avec pagination.
     */
    public function getProductsProperty()
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where(
                        'name',
                        'like',
                        '%' . $this->search . '%'
                    )
                    ->orWhere(
                        'slug',
                        'like',
                        '%' . $this->search . '%'
                    );
                });
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where(
                    'is_active',
                    $this->status === 'active'
                );
            })
            ->latest()
            ->paginate(10);
    }

    /**
     * Supprimer un produit.
     */
    public function delete(int $id): void
    {
        $product = Product::findOrFail($id);

        // Supprimer l'image du produit
        if (
            $product->image &&
            Storage::disk('public')->exists($product->image)
        ) {
            Storage::disk('public')->delete($product->image);
        }

        // Supprimer le produit
        $product->delete();

        session()->flash(
            'success',
            'Le produit a été supprimé avec succès.'
        );

        // Revenir à une page valide si nécessaire
        $this->resetPage();
    }
};
?>

<div class="admin-content">
     @section('title', 'Admin - Produits ')
    {{-- En-tête --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="page-title mb-1">
                Produits
            </h2>

            <p class="text-muted mb-0">
                Gérez les produits de votre boutique.
            </p>
        </div>

        <a
            href="{{ route('admin.produits.create') }}"
            class="btn btn-ak"
        >
            <i class="bi bi-plus-lg me-2"></i>
            Ajouter un produit
        </a>

    </div>


    {{-- Message de succès --}}
    @if (session()->has('success'))

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


    {{-- Filtres --}}
    <div class="card-admin mb-4">

        <div class="row g-3 align-items-end">

            {{-- Recherche --}}
            <div class="col-md-8">

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
                        wire:model.live="search"
                        placeholder="Rechercher un produit..."
                    >

                </div>

            </div>


            {{-- Statut --}}
            <div class="col-md-4">

                <label class="form-label">
                    Statut
                </label>

                <select
                    class="form-select"
                    wire:model.live="status"
                >
                    <option value="all">
                        Tous les produits
                    </option>

                    <option value="active">
                        Actifs
                    </option>

                    <option value="inactive">
                        Inactifs
                    </option>
                </select>

            </div>

        </div>

    </div>


    {{-- Tableau --}}
    <div class="card-admin">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>

                        <th>Produit</th>

                        <th>Catégorie</th>

                        <th>Prix</th>

                        <th>Stock</th>

                        <th>Statut</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($this->products as $product)

                        <tr>

                            {{-- Produit --}}
                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div class="product-image">

                                        @if ($product->image)

                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                            >

                                        @else

                                            <i class="bi bi-image text-muted"></i>

                                        @endif

                                    </div>

                                    <div>

                                        <div class="product-name">
                                            {{ $product->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $product->slug }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Catégorie --}}
                            <td>
                                {{ $product->category }}
                            </td>


                            {{-- Prix --}}
                            <td>

                                <strong>
                                    {{ number_format($product->price, 0, ',', ' ') }}
                                    FCFA
                                </strong>

                                @if ($product->old_price)

                                    <br>

                                    <small class="text-muted text-decoration-line-through">
                                        {{ number_format($product->old_price, 0, ',', ' ') }}
                                        FCFA
                                    </small>

                                @endif

                            </td>


                            {{-- Stock --}}
                            <td>

                                @if ($product->stock > 10)

                                    <span class="stock-ok">
                                        {{ $product->stock }}
                                    </span>

                                @elseif ($product->stock > 0)

                                    <span class="stock-warning">
                                        {{ $product->stock }}
                                    </span>

                                @else

                                    <span class="stock-danger">
                                        Rupture
                                    </span>

                                @endif

                            </td>


                            {{-- Statut --}}
                            <td>

                                @if ($product->is_active)

                                    <span class="status-active">
                                        Actif
                                    </span>

                                @else

                                    <span class="status-inactive">
                                        Inactif
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-2">

                                    {{-- Voir --}}
                                    <a
                                        href="{{ route('admin.produits.show', $product->id) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Voir"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- Modifier --}}
                                    <a
                                        href="{{ route('admin.produits.edit', $product->id) }}"
                                        class="btn btn-sm btn-outline-ak"
                                        title="Modifier"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Supprimer --}}
                                    <button
                                        type="button"
                                        wire:click="delete({{ $product->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer ce produit ?"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Supprimer"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                <div class="empty-products">

                                    <i class="bi bi-box-seam"></i>

                                    <h5>
                                        Aucun produit trouvé
                                    </h5>

                                    <p class="text-muted">
                                        Aucun produit ne correspond à votre recherche.
                                    </p>

                                    <a
                                        href="{{ route('admin.produits.create') }}"
                                        class="btn btn-ak"
                                    >
                                        <i class="bi bi-plus-lg me-2"></i>
                                        Ajouter un produit
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($this->products->hasPages())

            <div class="products-pagination">

                {{-- Informations --}}
                <div class="pagination-info">

                    @if ($this->products->total() > 0)

                        Affichage de

                        <strong>
                            {{ $this->products->firstItem() }}
                        </strong>

                        à

                        <strong>
                            {{ $this->products->lastItem() }}
                        </strong>

                        sur

                        <strong>
                            {{ $this->products->total() }}
                        </strong>

                        produits

                    @endif

                </div>


                {{-- Boutons --}}
                <div class="pagination-buttons">

                    {{-- Page précédente --}}
                    @if ($this->products->onFirstPage())

                        <span class="pagination-btn disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>

                    @else

                        <button
                            type="button"
                            wire:click="previousPage"
                            class="pagination-btn"
                        >
                            <i class="bi bi-chevron-left"></i>
                        </button>

                    @endif


                    {{-- Numéros des pages --}}
                    @foreach ($this->products->getUrlRange(1, $this->products->lastPage()) as $page => $url)

                        @if ($page == $this->products->currentPage())

                            <span class="pagination-btn active">
                                {{ $page }}
                            </span>

                        @else

                            <button
                                type="button"
                                wire:click="gotoPage({{ $page }})"
                                class="pagination-btn"
                            >
                                {{ $page }}
                            </button>

                        @endif

                    @endforeach


                    {{-- Page suivante --}}
                    @if ($this->products->hasMorePages())

                        <button
                            type="button"
                            wire:click="nextPage"
                            class="pagination-btn"
                        >
                            <i class="bi bi-chevron-right"></i>
                        </button>

                    @else

                        <span class="pagination-btn disabled">
                            <i class="bi bi-chevron-right"></i>
                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>