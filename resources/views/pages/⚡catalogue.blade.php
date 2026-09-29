<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    public string $recherche = '';
    public string $categorie = '';
    public int $prixMax = 500000;
    public string $tri = '';

    public function mount(): void
    {
        // Récupère la recherche envoyée par la navbar
        $this->recherche = request('q', '');
    }

    public function getProductsProperty()
    {
        $query = Product::query()
            ->where('is_active', true);

        if (!empty($this->recherche)) {
            $recherche = '%' . $this->recherche . '%';

            $query->where(function ($q) use ($recherche) {
                $q->where('name', 'like', $recherche)
                    ->orWhere('description', 'like', $recherche)
                    ->orWhere('category', 'like', $recherche);
            });
        }

        if (!empty($this->categorie)) {
            $query->where('category', $this->categorie);
        }

        $query->where('price', '<=', $this->prixMax);

        switch ($this->tri) {
            case 'asc':
                $query->orderBy('price', 'asc');
                break;

            case 'desc':
                $query->orderBy('price', 'desc');
                break;

            case 'name':
                $query->orderBy('name', 'asc');
                break;

            case 'popular':
                $query->orderBy('is_popular', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        return $query->get();
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
            session()->flash('error', 'Produit introuvable.');
            return;
        }

        if (!$product->is_active) {
            session()->flash('error', 'Ce produit n’est plus disponible.');
            return;
        }

        if ($product->stock <= 0) {
            session()->flash('error', 'Ce produit est en rupture de stock.');
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $quantity = $cart[$productId]['quantity'] ?? 1;

            if ($quantity >= $product->stock) {
                session()->flash(
                    'error',
                    'La quantité maximale disponible est atteinte.'
                );

                return;
            }

            $cart[$productId]['quantity'] = $quantity + 1;
        } else {
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

        session()->put('cart', $cart);

        // Informe les autres composants Livewire
        $this->dispatch('cart-updated');

        session()->flash(
            'success',
            $product->name . ' a été ajouté au panier.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AJOUTER AUX FAVORIS
    |--------------------------------------------------------------------------
    */

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

        // Informe la navbar que les favoris ont changé
        $this->dispatch('favoris-updated');

        session()->flash(
            'success',
            $product->name . ' a été ajouté à vos favoris.'
        );
    }
};
?>





<div>

    @section('title', 'Akatsuki - Produits')


    <main class="container py-5">


        {{-- =====================================================
        MESSAGES
        ====================================================== --}}

        @if (session()->has('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

        @endif


        @if (session()->has('error'))

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <i class="bi bi-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

        @endif



        <div class="row">


            {{-- =================================================
            SIDEBAR FILTRES
            ================================================== --}}

            <div class="col-lg-3 mb-4">


                <div class="card border-0 shadow-sm">

                    <div class="card-body">


                        <h5 class="fw-bold mb-4">

                            <i class="bi bi-funnel me-2"></i>

                            Filtres

                        </h5>



                        {{-- RECHERCHE --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Rechercher
                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-search"></i>

                                </span>


                                <input type="text" class="form-control" placeholder="Rechercher un produit..."
                                    wire:model.live.debounce.300ms="recherche">

                            </div>

                        </div>



                        {{-- CATÉGORIE --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Catégorie
                            </label>


                            <select class="form-select" wire:model.live="categorie">

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



                        {{-- PRIX --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Prix maximum
                            </label>


                            <input type="range" class="form-range" min="0" max="500000" step="5000"
                                wire:model.live="prixMax">


                            <div class="d-flex justify-content-between">

                                <small>
                                    0 FCFA
                                </small>


                                <strong>

                                    {{ number_format(
                                    $prixMax,
                                    0,
                                    ',',
                                    ' '
                                    ) }}

                                    FCFA

                                </strong>

                            </div>

                        </div>



                        {{-- TRI --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Trier par
                            </label>


                            <select class="form-select" wire:model.live="tri">

                                <option value="">
                                    Plus récents
                                </option>


                                <option value="popular">
                                    Produits populaires
                                </option>


                                <option value="asc">
                                    Prix croissant
                                </option>


                                <option value="desc">
                                    Prix décroissant
                                </option>


                                <option value="name">
                                    Nom A-Z
                                </option>

                            </select>

                        </div>



                        {{-- RESET --}}
                        <button type="button" class="btn btn-outline-dark rounded-pill w-100"
                            wire:click="$set('recherche', ''); $set('categorie', ''); $set('prixMax', 500000); $set('tri', '')">

                            <i class="bi bi-arrow-counterclockwise me-2"></i>

                            Réinitialiser

                        </button>


                    </div>

                </div>

            </div>



            {{-- =================================================
            CATALOGUE
            ================================================== --}}

            <div class="col-lg-9">


                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h1 class="fw-bold mb-1">
                            Nos produits
                        </h1>


                        <p class="text-muted mb-0">
                            Découvrez notre catalogue
                        </p>

                    </div>


                    <span class="badge bg-dark rounded-pill px-3 py-2">

                        {{ $this->products->count() }}

                        produit(s)

                    </span>

                </div>



                {{-- LOADING --}}
                <div wire:loading wire:target="recherche,categorie,prixMax,tri" class="text-center py-3">

                    <div class="spinner-border spinner-border-sm text-danger me-2"></div>

                    Chargement...

                </div>



                {{-- =================================================
                PRODUITS
                ================================================== --}}

                @if ($this->products->count() > 0)


                <div class="row g-4">


                    @foreach ($this->products as $product)


                    <div class="col-md-6 col-xl-4" wire:key="produit-{{ $product->id }}">


                        <div class="card h-100 border-0 shadow-sm overflow-hidden">


                            {{-- IMAGE --}}
                            <div class="position-relative">

                                {{-- FAVORI --}}
                                <button type="button" wire:click="ajouterAuxFavoris({{ $product->id }})"
                                    class="btn btn-light rounded-circle position-absolute top-0 start-0 m-2 shadow-sm"
                                    title="Ajouter aux favoris">
                                    <i class="bi bi-heart text-danger"></i>
                                </button>

                                {{-- IMAGE --}}
                                @if ($product->image)

                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height:220px; object-fit:cover;">

                                @else

                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="height:220px;">
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                </div>

                                @endif

                                {{-- STOCK --}}
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


                            {{-- INFORMATIONS --}}
                            <div class="card-body">


                                {{-- CATÉGORIE --}}
                                <small class="text-danger fw-semibold">
                                    {{ $product->category }}
                                </small>



                                {{-- NOM --}}
                                <h5 class="fw-bold mt-2 mb-2">
                                    {{ $product->name }}
                                </h5>



                                {{-- DESCRIPTION --}}
                                @if ($product->description)

                                <p class="text-muted small mb-3">

                                    {{ Str::limit(
                                    $product->description,
                                    70
                                    ) }}

                                </p>

                                @endif



                                {{-- PRIX --}}
                                <div>

                                    <span class="fw-bold fs-5">

                                        {{ number_format(
                                        $product->price,
                                        0,
                                        ',',
                                        ' '
                                        ) }}

                                        FCFA

                                    </span>


                                    @if (
                                    $product->old_price &&
                                    $product->old_price > $product->price
                                    )

                                    <del class="text-muted small ms-2">

                                        {{ number_format(
                                        $product->old_price,
                                        0,
                                        ',',
                                        ' '
                                        ) }}

                                        FCFA

                                    </del>

                                    @endif

                                </div>


                            </div>



                            {{-- ACTIONS --}}
                            <div class="card-footer bg-white border-0 pb-3">


                                <div class="d-flex gap-2">


                                    {{-- VOIR --}}
                                    <a href="{{ route('produitDetail', ['slug' => $product->slug]) }}"
                                        class="btn btn-outline-dark rounded-pill flex-grow-1">
                                        <i class="bi bi-eye me-1"></i>
                                        Voir
                                    </a>
                                    {{-- <a href="{{ route('produitDetail',$product->slug ) }}"
                                        class="btn btn-outline-dark rounded-pill flex-grow-1">

                                        <i class="bi bi-eye me-1"></i>

                                        Voir

                                    </a> --}}



                                    {{-- PANIER --}}
                                    <button type="button" wire:click="ajouterAuPanier({{ $product->id }})"
                                        wire:loading.attr="disabled" class="btn btn-dark rounded-pill"
                                        @disabled($product->stock <= 0)>

                                            <i class="bi bi-cart-plus"></i>

                                    </button>


                                </div>


                            </div>


                        </div>


                    </div>


                    @endforeach


                </div>


                @else


                {{-- =================================================
                AUCUN PRODUIT
                ================================================== --}}

                <div class="text-center py-5">


                    <i class="bi bi-search fs-1 text-muted"></i>


                    <h4 class="mt-3">
                        Aucun produit trouvé
                    </h4>


                    <p class="text-muted">
                        Essayez de modifier vos critères de recherche.
                    </p>


                    <button type="button" class="btn btn-dark rounded-pill px-4"
                        wire:click="$set('recherche', ''); $set('categorie', ''); $set('prixMax', 500000); $set('tri', '')">

                        Réinitialiser les filtres

                    </button>


                </div>


                @endif


            </div>

        </div>

    </main>

</div>