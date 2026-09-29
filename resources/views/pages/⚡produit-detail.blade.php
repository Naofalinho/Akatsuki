<?php

use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    public Product $product;

    public int $quantite = 1;

    public function mount(string $slug): void
    {
        $this->product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function augmenter(): void
    {
        if ($this->quantite < $this->product->stock) {
            $this->quantite++;
        }
    }

    public function diminuer(): void
    {
        if ($this->quantite > 1) {
            $this->quantite--;
        }
    }

    public function ajouterAuPanier(): void
    {
        if (!$this->product->is_active) {
            session()->flash('error', 'Ce produit n’est plus disponible.');
            return;
        }

        if ($this->product->stock <= 0) {
            session()->flash('error', 'Ce produit est en rupture de stock.');
            return;
        }

        if ($this->quantite > $this->product->stock) {
            $this->quantite = $this->product->stock;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$this->product->id])) {

            $ancienneQuantite = $cart[$this->product->id]['quantity'] ?? 0;

            $nouvelleQuantite = $ancienneQuantite + $this->quantite;

            if ($nouvelleQuantite > $this->product->stock) {
                $nouvelleQuantite = $this->product->stock;
            }

            $cart[$this->product->id]['quantity'] = $nouvelleQuantite;

        } else {

            $cart[$this->product->id] = [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'old_price' => $this->product->old_price,
                'image' => $this->product->image,
                'slug' => $this->product->slug,
                'quantity' => $this->quantite,
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('cart-updated');

        session()->flash(
            'success',
            $this->product->name . ' a été ajouté au panier.'
        );
    }

    public function ajouterAuxFavoris(): void
    {
        $favoris = session()->get('favoris', []);

        if (in_array($this->product->id, $favoris)) {

            $favoris = array_values(
                array_filter(
                    $favoris,
                    fn ($id) => (int) $id !== (int) $this->product->id
                )
            );

            session()->put('favoris', $favoris);

            $this->dispatch('favoris-updated');

            session()->flash(
                'success',
                'Produit retiré de vos favoris.'
            );

            return;
        }

        $favoris[] = $this->product->id;

        session()->put('favoris', $favoris);

        $this->dispatch('favoris-updated');

        session()->flash(
            'success',
            'Produit ajouté à vos favoris.'
        );
    }

    public function getEstFavoriProperty(): bool
    {
        return in_array(
            $this->product->id,
            session()->get('favoris', [])
        );
    }
};
?>

<div>

    @section('title', 'Akatsuki - Détail Produit')

    {{-- MESSAGE SUCCÈS --}}
    @if (session()->has('success'))
        <div
            class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow"
            style="z-index: 9999; min-width: 320px;"
            role="alert"
        >
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Fermer"
            ></button>
        </div>
    @endif

    {{-- MESSAGE ERREUR --}}
    @if (session()->has('error'))
        <div
            class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow"
            style="z-index: 9999; min-width: 320px;"
            role="alert"
        >
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Fermer"
            ></button>
        </div>
    @endif

    {{-- PAGE PRODUIT --}}
    <section class="py-5 bg-light">

        <div class="container">

            {{-- RETOUR --}}
            <div class="mb-4">

                <a
                    href="{{ route('catalogue') }}"
                    class="text-decoration-none text-dark fw-semibold"
                >
                    <i class="bi bi-arrow-left me-2"></i>
                    Retour au catalogue
                </a>

            </div>

            <div class="row g-5 align-items-start">

                {{-- IMAGE --}}
                <div class="col-lg-6">

                    <div class="bg-white rounded-4 shadow-sm p-4 position-relative">

                        @if ($product->is_popular)

                            <span
                                class="badge bg-dark position-absolute top-0 start-0 m-3 px-3 py-2"
                            >
                                <i class="bi bi-star-fill me-1"></i>
                                Populaire
                            </span>

                        @endif

                        @if ($product->discount_percentage)

                            <span
                                class="badge bg-danger position-absolute top-0 end-0 m-3 px-3 py-2"
                            >
                                -{{ $product->discount_percentage }}%
                            </span>

                        @endif

                        <div
                            class="d-flex justify-content-center align-items-center"
                            style="min-height: 500px;"
                        >

                            @if ($product->image)

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid"
                                    style="
                                        max-height: 500px;
                                        width: 100%;
                                        object-fit: contain;
                                    "
                                >

                            @else

                                <div class="text-center text-muted">

                                    <i
                                        class="bi bi-image"
                                        style="font-size: 6rem;"
                                    ></i>

                                    <p class="mt-3 mb-0">
                                        Aucune image disponible
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

                {{-- INFORMATIONS --}}
                <div class="col-lg-6">

                    @if ($product->category)

                        <div class="mb-2">

                            <span class="text-uppercase text-muted small fw-semibold">
                                {{ $product->category }}
                            </span>

                        </div>

                    @endif

                    <h1 class="fw-bold mb-3">
                        {{ $product->name }}
                    </h1>

                    {{-- NOTE --}}
                    <div class="d-flex align-items-center gap-2 mb-4">

                        <div class="text-warning">

                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>

                        </div>

                        <span class="text-muted small">
                            Produit AKATSUKI
                        </span>

                    </div>

                    {{-- PRIX --}}
                    <div class="mb-4">

                        <div class="d-flex align-items-center gap-3 flex-wrap">

                            <span class="fs-2 fw-bold text-dark">
                                {{ number_format((float) $product->price, 0, ',', ' ') }}
                                FCFA
                            </span>

                            @if (
                                $product->old_price &&
                                $product->old_price > $product->price
                            )

                                <span class="text-muted text-decoration-line-through fs-5">
                                    {{ number_format((float) $product->old_price, 0, ',', ' ') }}
                                    FCFA
                                </span>

                            @endif

                        </div>

                    </div>

                    {{-- DESCRIPTION --}}
                    @if ($product->description)

                        <div class="mb-4">

                            <h5 class="fw-bold mb-3">
                                Description
                            </h5>

                            <p class="text-muted lh-lg mb-0">
                                {{ $product->description }}
                            </p>

                        </div>

                    @endif

                    {{-- STOCK --}}
                    <div class="mb-4">

                        @if ($product->stock > 0)

                            <div class="d-flex align-items-center gap-2 text-success">

                                <i class="bi bi-check-circle-fill"></i>

                                <span class="fw-semibold">
                                    En stock
                                </span>

                                <span class="text-muted">
                                    ({{ $product->stock }}
                                    disponible{{ $product->stock > 1 ? 's' : '' }})
                                </span>

                            </div>

                        @else

                            <div class="d-flex align-items-center gap-2 text-danger">

                                <i class="bi bi-x-circle-fill"></i>

                                <span class="fw-semibold">
                                    Rupture de stock
                                </span>

                            </div>

                        @endif

                    </div>

                    {{-- ACTIONS --}}
                    @if ($product->stock > 0)

                        <div class="d-flex flex-wrap gap-3 align-items-center mb-4">

                            {{-- QUANTITÉ --}}
                            <div class="d-flex align-items-center border rounded-pill overflow-hidden bg-white">

                                <button
                                    type="button"
                                    wire:click="diminuer"
                                    class="btn btn-light border-0 px-3"
                                    aria-label="Diminuer la quantité"
                                >
                                    <i class="bi bi-dash"></i>
                                </button>

                                <span
                                    class="px-3 fw-bold"
                                    style="min-width: 50px; text-align: center;"
                                >
                                    {{ $quantite }}
                                </span>

                                <button
                                    type="button"
                                    wire:click="augmenter"
                                    class="btn btn-light border-0 px-3"
                                    aria-label="Augmenter la quantité"
                                >
                                    <i class="bi bi-plus"></i>
                                </button>

                            </div>

                            {{-- PANIER --}}
                            <button
                                type="button"
                                wire:click="ajouterAuPanier"
                                wire:loading.attr="disabled"
                                class="btn btn-dark rounded-pill px-4 py-3 fw-semibold"
                            >

                                <span
                                    wire:loading.remove
                                    wire:target="ajouterAuPanier"
                                >
                                    <i class="bi bi-bag-plus me-2"></i>
                                    Ajouter au panier
                                </span>

                                <span
                                    wire:loading
                                    wire:target="ajouterAuPanier"
                                >
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Ajout...
                                </span>

                            </button>

                            {{-- FAVORIS --}}
                            <button
                                type="button"
                                wire:click="ajouterAuxFavoris"
                                wire:loading.attr="disabled"
                                class="btn rounded-circle d-flex align-items-center justify-content-center {{ $this->estFavori ? 'btn-danger' : 'btn-outline-dark' }}"
                                style="width: 52px; height: 52px;"
                                title="{{ $this->estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                            >

                                <i class="bi {{ $this->estFavori ? 'bi-heart-fill' : 'bi-heart' }}"></i>

                            </button>

                        </div>

                    @else

                        <button
                            type="button"
                            class="btn btn-secondary rounded-pill px-4 py-3"
                            disabled
                        >
                            <i class="bi bi-x-circle me-2"></i>
                            Produit indisponible
                        </button>

                    @endif

                    {{-- PANIER --}}
                    <div class="mt-3">

                        <a
                            href="{{ route('panier') }}"
                            class="text-decoration-none text-dark fw-semibold"
                        >
                            <i class="bi bi-bag me-2"></i>
                            Voir mon panier
                        </a>

                    </div>

                    {{-- INFORMATIONS --}}
                    <div class="border-top mt-4 pt-4">

                        <div class="row g-3">

                            <div class="col-12">

                                <div class="d-flex gap-3">

                                    <i class="bi bi-truck fs-4"></i>

                                    <div>

                                        <strong>
                                            Livraison
                                        </strong>

                                        <p class="text-muted small mb-0">
                                            Livraison disponible selon votre zone.
                                        </p>

                                    </div>

                                </div>

                            </div>

                            <div class="col-12">

                                <div class="d-flex gap-3">

                                    <i class="bi bi-shield-check fs-4"></i>

                                    <div>

                                        <strong>
                                            Paiement sécurisé
                                        </strong>

                                        <p class="text-muted small mb-0">
                                            Vos commandes sont traitées en toute sécurité.
                                        </p>

                                    </div>

                                </div>

                            </div>

                            <div class="col-12">

                                <div class="d-flex gap-3">

                                    <i class="bi bi-arrow-repeat fs-4"></i>

                                    <div>

                                        <strong>
                                            Service client
                                        </strong>

                                        <p class="text-muted small mb-0">
                                            Une assistance est disponible pour vos commandes.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>