<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('layouts.admin')] class extends Component
{
    use WithFileUploads;

    public Product $product;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $category = '';

    #[Validate('nullable|string|max:5000')]
    public string $description = '';

    #[Validate('required|numeric|min:0')]
    public $price = '';

    #[Validate('nullable|numeric|min:0')]
    public $old_price = '';

    #[Validate('required|integer|min:0')]
    public $stock = 0;

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:2048')]
    public $newImage = null;

    public bool $is_popular = false;

    public bool $is_active = true;

    public function mount(Product $product): void
    {
        $this->product = $product;

        $this->name = $product->name;
        $this->category = $product->category;
        $this->description = $product->description ?? '';
        $this->price = $product->price;
        $this->old_price = $product->old_price;
        $this->stock = $product->stock;
        $this->is_popular = $product->is_popular;
        $this->is_active = $product->is_active;
    }

    public function update(): void
    {
        $this->validate();

        $imagePath = $this->product->image;

        if ($this->newImage) {

            if (
                $this->product->image &&
                Storage::disk('public')->exists($this->product->image)
            ) {
                Storage::disk('public')->delete(
                    $this->product->image
                );
            }

            $imagePath = $this->newImage->store(
                'products',
                'public'
            );
        }

        $this->product->update([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'category' => $this->category,
            'price' => $this->price,
            'old_price' => $this->old_price ?: null,
            'image' => $imagePath,
            'stock' => $this->stock,
            'is_popular' => $this->is_popular,
            'is_active' => $this->is_active,
        ]);

        session()->flash(
            'success',
            'Le produit a été modifié avec succès.'
        );

        $this->redirectRoute('admin.produits');
    }
};
?>

<div class="admin-content">
    @section('title', 'Admin -Modifier le produit ')

    {{-- ===================================================== --}}
    {{-- EN-TÊTE --}}
    {{-- ===================================================== --}}

    <div class="page-title mb-4">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div>

                <div class="small text-danger fw-bold mb-1">
                    CATALOGUE
                </div>

                <h1 class="mb-1">
                    Modifier le produit
                </h1>

                <p class="muted mb-0">
                    Modifiez les informations de votre produit.
                </p>

            </div>

            <a
                href="{{ route('admin.produits') }}"
                class="btn btn-outline-dark"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Retour aux produits
            </a>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- MESSAGE DE SUCCÈS --}}
    {{-- ===================================================== --}}

    @if(session()->has('success'))

        <div
            class="alert alert-success alert-dismissible fade show mb-4"
            role="alert"
        >

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Fermer"
            ></button>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- FORMULAIRE --}}
    {{-- ===================================================== --}}

    <form wire:submit="update">

        <div class="row g-4">

            {{-- ================================================= --}}
            {{-- COLONNE PRINCIPALE --}}
            {{-- ================================================= --}}

            <div class="col-xl-8">

                {{-- ============================================= --}}
                {{-- INFORMATIONS DU PRODUIT --}}
                {{-- ============================================= --}}

                <div class="card-admin">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Informations du produit
                            </h5>

                            <small class="text-muted">
                                Modifiez les informations principales.
                            </small>

                        </div>

                        <div class="admin-form-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                    </div>


                    {{-- Nom --}}
                    <div class="mb-4">

                        <label
                            for="name"
                            class="form-label"
                        >
                            Nom du produit
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            id="name"
                            type="text"
                            wire:model="name"
                            class="form-control @error('name') is-invalid @enderror"
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Catégorie + Stock --}}
                    <div class="row g-3">

                        {{-- Catégorie --}}
                        <div class="col-md-6">

                            <label
                                for="category"
                                class="form-label"
                            >
                                Catégorie
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="category"
                                wire:model="category"
                                class="form-select @error('category') is-invalid @enderror"
                            >

                                <option value="">
                                    Sélectionner une catégorie
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

                            @error('category')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Stock --}}
                        <div class="col-md-6">

                            <label
                                for="stock"
                                class="form-label"
                            >
                                Stock
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                id="stock"
                                type="number"
                                min="0"
                                wire:model="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                            >

                            @error('stock')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="mt-4">

                        <label
                            for="description"
                            class="form-label"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            wire:model="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="6"
                        ></textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ============================================= --}}
                {{-- TARIFICATION --}}
                {{-- ============================================= --}}

                <div class="card-admin mt-4">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Tarification
                            </h5>

                            <small class="text-muted">
                                Prix actuel et ancien prix.
                            </small>

                        </div>

                        <div class="admin-form-icon">
                            <i class="bi bi-currency-exchange"></i>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- Prix actuel --}}
                        <div class="col-md-6">

                            <label
                                for="price"
                                class="form-label"
                            >
                                Prix actuel
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <input
                                    id="price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    wire:model="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                >

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                            @error('price')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Ancien prix --}}
                        <div class="col-md-6">

                            <label
                                for="old_price"
                                class="form-label"
                            >
                                Ancien prix
                            </label>

                            <div class="input-group">

                                <input
                                    id="old_price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    wire:model="old_price"
                                    class="form-control @error('old_price') is-invalid @enderror"
                                >

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                            @error('old_price')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ============================================= --}}
                {{-- IMAGE DU PRODUIT --}}
                {{-- ============================================= --}}

                <div class="card-admin mt-4">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Image du produit
                            </h5>

                            <small class="text-muted">
                                Remplacez l'image actuelle si nécessaire.
                            </small>

                        </div>

                        <div class="admin-form-icon">
                            <i class="bi bi-image"></i>
                        </div>

                    </div>


                    {{-- ========================================= --}}
                    {{-- IMAGE ACTUELLE --}}
                    {{-- ========================================= --}}

                    @if($product->image && !$newImage)

                        <div class="current-product-image-wrapper">

                            <div class="current-product-image">

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    loading="lazy"
                                >

                            </div>

                            <div class="image-caption">

                                <i class="bi bi-image me-1"></i>

                                Image actuelle

                            </div>

                        </div>

                    @endif


                    {{-- ========================================= --}}
                    {{-- UPLOAD IMAGE --}}
                    {{-- ========================================= --}}

                    <div class="product-upload-area">

                        <input
                            type="file"
                            id="newImage"
                            wire:model="newImage"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="d-none"
                        >


                        <label
                            for="newImage"
                            class="product-upload-label"
                        >

                            @if($newImage)

                                {{-- Nouvelle image --}}
                                <div class="product-preview">

                                    <img
                                        src="{{ $newImage->temporaryUrl() }}"
                                        alt="Nouvelle image du produit"
                                    >

                                </div>

                                <div class="mt-3">

                                    <strong>
                                        Nouvelle image sélectionnée
                                    </strong>

                                    <small class="d-block text-muted mt-1">
                                        Cliquez pour la remplacer
                                    </small>

                                </div>

                            @else

                                {{-- Zone d'upload --}}
                                <div class="upload-icon">

                                    <i class="bi bi-cloud-arrow-up"></i>

                                </div>

                                <strong>
                                    Cliquer pour choisir une nouvelle image
                                </strong>

                                <small>
                                    JPG, PNG ou WEBP — 2 Mo maximum
                                </small>

                            @endif

                        </label>

                    </div>


                    {{-- Loading upload --}}
                    <div
                        wire:loading
                        wire:target="newImage"
                        class="text-muted small mt-2"
                    >

                        <span class="spinner-border spinner-border-sm me-2"></span>

                        Chargement de l'image...

                    </div>


                    {{-- Erreur image --}}
                    @error('newImage')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- COLONNE DROITE --}}
            {{-- ================================================= --}}

            <div class="col-xl-4">

                {{-- ============================================= --}}
                {{-- PUBLICATION --}}
                {{-- ============================================= --}}

                <div class="card-admin">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Publication
                            </h5>

                            <small class="text-muted">
                                Contrôlez la visibilité.
                            </small>

                        </div>

                        <div class="admin-form-icon">
                            <i class="bi bi-eye"></i>
                        </div>

                    </div>


                    {{-- Produit actif --}}
                    <div class="status-switch">

                        <div>

                            <strong>
                                Produit actif
                            </strong>

                            <small>
                                Visible dans la boutique
                            </small>

                        </div>

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                wire:model="is_active"
                                id="is_active"
                            >

                        </div>

                    </div>


                    {{-- Produit populaire --}}
                    <div class="status-switch mt-3">

                        <div>

                            <strong>
                                Produit populaire
                            </strong>

                            <small>
                                Afficher parmi les produits populaires
                            </small>

                        </div>

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                wire:model="is_popular"
                                id="is_popular"
                            >

                        </div>

                    </div>

                </div>


                {{-- ============================================= --}}
                {{-- INFORMATIONS ACTUELLES --}}
                {{-- ============================================= --}}

                <div class="card-admin mt-4">

                    <h5 class="mb-3">
                        Informations actuelles
                    </h5>

                    <div class="product-summary">

                        {{-- ID --}}
                        <div>

                            <span>
                                ID
                            </span>

                            <strong>
                                #{{ $product->id }}
                            </strong>

                        </div>


                        {{-- Slug --}}
                        <div>

                            <span>
                                Slug
                            </span>

                            <strong class="text-break">
                                {{ $product->slug }}
                            </strong>

                        </div>


                        {{-- Disponibilité --}}
                        <div>

                            <span>
                                Disponibilité
                            </span>

                            @if($stock > 0 && $is_active)

                                <strong class="text-success">
                                    Disponible
                                </strong>

                            @else

                                <strong class="text-danger">
                                    Indisponible
                                </strong>

                            @endif

                        </div>


                        {{-- Stock --}}
                        <div>

                            <span>
                                Stock
                            </span>

                            <strong>
                                {{ $stock }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- ============================================= --}}
                {{-- ACTIONS --}}
                {{-- ============================================= --}}

                <div class="card-admin mt-4">

                    <button
                        type="submit"
                        class="btn btn-ak w-100"
                        wire:loading.attr="disabled"
                        wire:target="update"
                    >

                        <span
                            wire:loading.remove
                            wire:target="update"
                        >

                            <i class="bi bi-check-lg me-2"></i>

                            Enregistrer les modifications

                        </span>


                        <span
                            wire:loading
                            wire:target="update"
                        >

                            <span class="spinner-border spinner-border-sm me-2"></span>

                            Enregistrement...

                        </span>

                    </button>


                    <a
                        href="{{ route('admin.produits') }}"
                        class="btn btn-light w-100 mt-2"
                    >

                        <i class="bi bi-x-lg me-2"></i>

                        Annuler

                    </a>

                </div>

            </div>

        </div>

    </form>

</div>