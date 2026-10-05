<?php

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('layouts.admin')] class extends Component
{
    use WithFileUploads;

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
    public $image = null;

    public bool $is_popular = false;

    public bool $is_active = true;


    /**
     * Enregistre le produit.
     */
    public function save(): void
    {
        $this->validate();

        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        Product::create([
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
            'Le produit a été ajouté avec succès.'
        );

        $this->reset([
            'name',
            'category',
            'description',
            'price',
            'old_price',
            'stock',
            'image',
        ]);

        $this->is_popular = false;
        $this->is_active = true;
    }
};
?>

<div class="admin-content">

    {{-- En-tête --}}
    <div class="page-title mb-4">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div>

                <div class="small text-danger fw-bold">
                    CATALOGUE
                </div>

                <h1>
                    Ajouter un produit
                </h1>

                <p class="muted mb-0">
                    Ajoutez un nouveau produit à votre boutique Akatsuki.
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


    {{-- Message de succès --}}
    @if (session()->has('success'))

        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 mb-4">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    <form wire:submit="save">

        <div class="row g-4">

            {{-- COLONNE PRINCIPALE --}}
            <div class="col-xl-8">

                {{-- Informations --}}
                <div class="card-admin">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Informations du produit
                            </h5>

                            <small class="text-muted">
                                Renseignez les informations principales du produit.
                            </small>

                        </div>

                        <div class="admin-form-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                    </div>


                    {{-- Nom --}}
                    <div class="mb-4">

                        <label for="name" class="form-label">
                            Nom du produit
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            id="name"
                            type="text"
                            wire:model="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Ex : T-shirt Akatsuki Premium"
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="row g-3">

                        {{-- Catégorie --}}
                        <div class="col-md-6">

                            <label for="category" class="form-label">
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

                            <label for="stock" class="form-label">
                                Stock
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                id="stock"
                                type="number"
                                min="0"
                                wire:model="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                                placeholder="20"
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

                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            id="description"
                            wire:model="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="6"
                            placeholder="Décrivez votre produit..."
                        ></textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Prix --}}
                <div class="card-admin mt-4">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Tarification
                            </h5>

                            <small class="text-muted">
                                Définissez le prix actuel et éventuellement l'ancien prix.
                            </small>

                        </div>

                        <div class="admin-form-icon">
                            <i class="bi bi-currency-exchange"></i>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- Prix actuel --}}
                        <div class="col-md-6">

                            <label for="price" class="form-label">
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
                                    placeholder="25000"
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

                            <label for="old_price" class="form-label">
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
                                    placeholder="30000"
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


                {{-- Image --}}
                <div class="card-admin mt-4">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Image du produit
                            </h5>

                            <small class="text-muted">
                                JPG, PNG ou WEBP — maximum 2 Mo.
                            </small>

                        </div>

                        <div class="admin-form-icon">
                            <i class="bi bi-image"></i>
                        </div>

                    </div>


                    <div class="product-upload-area">

                        <input
                            type="file"
                            id="image"
                            wire:model="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="d-none"
                        >

                        <label
                            for="image"
                            class="product-upload-label"
                        >

                            @if ($image)

                                <div class="product-preview">

                                    <img
                                        src="{{ $image->temporaryUrl() }}"
                                        alt="Aperçu du produit"
                                    >

                                </div>

                                <div class="mt-3">

                                    <strong>
                                        Image sélectionnée
                                    </strong>

                                    <small class="d-block text-muted">
                                        Cliquez pour remplacer l'image
                                    </small>

                                </div>

                            @else

                                <div class="upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>

                                <strong>
                                    Cliquez pour sélectionner une image
                                </strong>

                                <small>
                                    JPG, PNG ou WEBP
                                </small>

                            @endif

                        </label>

                    </div>

                    @error('image')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                    @enderror

                    <div
                        wire:loading
                        wire:target="image"
                        class="small text-muted mt-2"
                    >
                        <i class="bi bi-arrow-repeat"></i>
                        Chargement de l'image...
                    </div>

                </div>

            </div>


            {{-- COLONNE DROITE --}}
            <div class="col-xl-4">

                {{-- Publication --}}
                <div class="card-admin">

                    <div class="admin-form-header">

                        <div>

                            <h5 class="mb-1">
                                Publication
                            </h5>

                            <small class="text-muted">
                                Contrôlez la visibilité du produit.
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
                                Afficher dans les produits populaires
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


                {{-- Aperçu --}}
                <div class="card-admin mt-4">

                    <h5 class="mb-3">
                        Aperçu
                    </h5>

                    <div class="product-summary">

                        <div>

                            <span>
                                Produit
                            </span>

                            <strong>
                                {{ $name ?: '—' }}
                            </strong>

                        </div>

                        <div>

                            <span>
                                Catégorie
                            </span>

                            <strong>
                                {{ $category ?: '—' }}
                            </strong>

                        </div>

                        <div>

                            <span>
                                Prix
                            </span>

                            <strong>
                                {{ $price !== '' ? number_format((float) $price, 0, ',', ' ') . ' FCFA' : '—' }}
                            </strong>

                        </div>

                        <div>

                            <span>
                                Ancien prix
                            </span>

                            <strong>
                                {{ $old_price !== '' ? number_format((float) $old_price, 0, ',', ' ') . ' FCFA' : '—' }}
                            </strong>

                        </div>

                        <div>

                            <span>
                                Stock
                            </span>

                            <strong>
                                {{ $stock }}
                            </strong>

                        </div>

                        <div>

                            <span>
                                Statut
                            </span>

                            @if($is_active)

                                <strong class="text-success">
                                    Actif
                                </strong>

                            @else

                                <strong class="text-muted">
                                    Inactif
                                </strong>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Enregistrement --}}
                <div class="card-admin mt-4">

                    <button
                        type="submit"
                        class="btn btn-ak w-100"
                        wire:loading.attr="disabled"
                        wire:target="save"
                    >

                        <span
                            wire:loading.remove
                            wire:target="save"
                        >
                            <i class="bi bi-check-lg me-2"></i>
                            Enregistrer le produit
                        </span>

                        <span
                            wire:loading
                            wire:target="save"
                        >
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Enregistrement...
                        </span>

                    </button>


                    <a
                        href="{{ route('admin.produits') }}"
                        class="btn btn-light w-100 mt-2"
                    >
                        Annuler
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>