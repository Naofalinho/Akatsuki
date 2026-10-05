<?php

use App\Models\Order;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.admin')]
#[Title('Détails de la commande')]
class extends Component
{
    public Order $order;

    public string $status = '';

    public function mount(Order $order): void
    {
        $this->order = $order->load('items');

        $this->status = $order->status;
    }

    /**
     * Modifier le statut de la commande.
     */
    public function updateStatus(): void
    {
        $this->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'processing',
                    'shipping',
                    'delivered',
                    'cancelled',
                ]),
            ],
        ]);

        $this->order->update([
            'status' => $this->status,
        ]);

        $this->order->refresh();

        session()->flash(
            'success',
            'Le statut de la commande a été mis à jour avec succès.'
        );
    }
};
?>

<div>

   @section('title', 'Admin -la commande')

    <div class="container-fluid px-4 py-4">

        {{-- ========================================== --}}
        {{-- MESSAGE DE SUCCÈS --}}
        {{-- ========================================== --}}

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


        {{-- ========================================== --}}
        {{-- EN-TÊTE --}}
        {{-- ========================================== --}}

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4"
        >

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <a
                        href="{{ route('admin.commandes') }}"
                        class="text-decoration-none text-muted"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Commandes
                    </a>

                    <span class="text-muted">
                        /
                    </span>

                    <span class="text-muted">
                        {{ $order->order_number }}
                    </span>

                </div>


                <h2 class="fw-bold mb-1">
                    Commande {{ $order->order_number }}
                </h2>


                <p class="text-muted mb-0">

                    Créée le
                    {{ $order->created_at->format('d/m/Y à H:i') }}

                </p>

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('admin.commandes') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour aux commandes
                </a>

            </div>

        </div>


        {{-- ========================================== --}}
        {{-- INFORMATIONS PRINCIPALES --}}
        {{-- ========================================== --}}

        <div class="row g-4">


            {{-- ====================================== --}}
            {{-- INFORMATIONS CLIENT --}}
            {{-- ====================================== --}}

            <div class="col-lg-6">

                <div class="card card-admin border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div
                                class="d-flex align-items-center justify-content-center rounded"
                                style="
                                    width:45px;
                                    height:45px;
                                    background:#fde8e8;
                                    color:#e50914;
                                "
                            >

                                <i class="bi bi-person fs-5"></i>

                            </div>


                            <div>

                                <h5 class="fw-bold mb-0">
                                    Informations client
                                </h5>

                                <small class="text-muted">
                                    Coordonnées du client
                                </small>

                            </div>

                        </div>


                        {{-- NOM --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Nom complet
                            </small>

                            <strong>
                                {{ $order->customer_name }}
                            </strong>

                        </div>


                        {{-- EMAIL --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Adresse email
                            </small>

                            <a
                                href="mailto:{{ $order->email }}"
                                class="text-decoration-none"
                            >
                                {{ $order->email }}
                            </a>

                        </div>


                        {{-- TELEPHONE --}}

                        <div>

                            <small class="text-muted d-block mb-1">
                                Téléphone
                            </small>

                            <a
                                href="tel:{{ $order->phone }}"
                                class="text-decoration-none"
                            >
                                {{ $order->phone }}
                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================== --}}
            {{-- LIVRAISON --}}
            {{-- ====================================== --}}

            <div class="col-lg-6">

                <div class="card card-admin border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            <div
                                class="d-flex align-items-center justify-content-center rounded"
                                style="
                                    width:45px;
                                    height:45px;
                                    background:#fde8e8;
                                    color:#e50914;
                                "
                            >

                                <i class="bi bi-truck fs-5"></i>

                            </div>


                            <div>

                                <h5 class="fw-bold mb-0">
                                    Livraison
                                </h5>

                                <small class="text-muted">
                                    Adresse et mode de livraison
                                </small>

                            </div>

                        </div>


                        {{-- MODE DE LIVRAISON --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Mode de livraison
                            </small>


                            @if($order->delivery_method === 'home_delivery')

                                <span class="badge bg-primary">

                                    <i class="bi bi-truck me-1"></i>

                                    Livraison à domicile

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    <i class="bi bi-shop me-1"></i>

                                    Retrait sur place

                                </span>

                            @endif

                        </div>


                        {{-- PAYS --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Pays
                            </small>

                            <strong>
                                {{ $order->country }}
                            </strong>

                        </div>


                        {{-- VILLE --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Ville
                            </small>

                            <span>
                                {{ $order->city }}
                            </span>

                        </div>


                        {{-- QUARTIER --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Quartier
                            </small>

                            <span>
                                {{ $order->neighborhood }}
                            </span>

                        </div>


                        {{-- ADRESSE --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Adresse complète
                            </small>

                            <strong>
                                {{ $order->address }}
                            </strong>

                        </div>


                        {{-- POINT DE REPERE --}}

                        @if($order->landmark)

                            <div>

                                <small class="text-muted d-block mb-1">
                                    Point de repère
                                </small>

                                <span>
                                    {{ $order->landmark }}
                                </span>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ====================================== --}}
            {{-- PAIEMENT --}}
            {{-- ====================================== --}}

            <div class="col-lg-4">

                <div class="card card-admin border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="bi bi-wallet2 text-danger me-2"></i>

                            Paiement

                        </h5>


                        {{-- MODE DE PAIEMENT --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">
                                Mode de paiement
                            </small>


                            @switch($order->payment_method)

                                @case('cash_on_delivery')

                                    <strong>
                                        Paiement à la livraison
                                    </strong>

                                    @break

                                @case('mtn_momo')

                                    <strong>
                                        MTN Mobile Money
                                    </strong>

                                    @break

                                @case('moov_money')

                                    <strong>
                                        Moov Money
                                    </strong>

                                    @break

                                @default

                                    <strong>
                                        Non défini
                                    </strong>

                            @endswitch

                        </div>


                        {{-- STATUT PAIEMENT --}}

                        <div>

                            <small class="text-muted d-block mb-2">
                                Statut du paiement
                            </small>


                            @switch($order->payment_status)

                                @case('paid')

                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Payé
                                    </span>

                                    @break

                                @case('failed')

                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Échec
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock me-1"></i>
                                        En attente
                                    </span>

                            @endswitch

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================== --}}
            {{-- STATUT DE LA COMMANDE --}}
            {{-- ====================================== --}}

            <div class="col-lg-4">

                <div class="card card-admin border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="bi bi-box-seam text-danger me-2"></i>

                            Statut de la commande

                        </h5>


                        {{-- STATUT ACTUEL --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-2">
                                Statut actuel
                            </small>


                            @switch($order->status)

                                @case('pending')

                                    <span class="order-status order-pending">
                                        <i class="bi bi-clock me-1"></i>
                                        En attente
                                    </span>

                                    @break

                                @case('confirmed')

                                    <span class="order-status order-confirmed">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Confirmée
                                    </span>

                                    @break

                                @case('processing')

                                    <span class="order-status order-confirmed">
                                        <i class="bi bi-box-seam me-1"></i>
                                        En préparation
                                    </span>

                                    @break

                                @case('shipping')

                                    <span class="order-status order-shipped">
                                        <i class="bi bi-truck me-1"></i>
                                        En livraison
                                    </span>

                                    @break

                                @case('delivered')

                                    <span class="order-status order-delivered">
                                        <i class="bi bi-check2-all me-1"></i>
                                        Livrée
                                    </span>

                                    @break

                                @case('cancelled')

                                    <span class="order-status order-cancelled">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Annulée
                                    </span>

                                    @break

                                @default

                                    <span class="order-status">
                                        {{ $order->status }}
                                    </span>

                            @endswitch

                        </div>


                        <hr>


                        {{-- FORMULAIRE STATUT --}}

                        <form wire:submit="updateStatus">

                            <label class="form-label fw-semibold">
                                Modifier le statut
                            </label>


                            <select
                                wire:model="status"
                                class="form-select @error('status') is-invalid @enderror mb-3"
                            >

                                <option value="pending">
                                    En attente
                                </option>

                                <option value="confirmed">
                                    Confirmée
                                </option>

                                <option value="processing">
                                    En préparation
                                </option>

                                <option value="shipping">
                                    En livraison
                                </option>

                                <option value="delivered">
                                    Livrée
                                </option>

                                <option value="cancelled">
                                    Annulée
                                </option>

                            </select>


                            @error('status')

                                <div class="invalid-feedback d-block mb-3">
                                    {{ $message }}
                                </div>

                            @enderror


                            <button
                                type="submit"
                                class="btn btn-dark w-100"
                                wire:loading.attr="disabled"
                                wire:target="updateStatus"
                            >

                                <span
                                    wire:loading.remove
                                    wire:target="updateStatus"
                                >
                                    <i class="bi bi-check-lg me-1"></i>
                                    Enregistrer le statut
                                </span>


                                <span
                                    wire:loading
                                    wire:target="updateStatus"
                                >

                                    <span
                                        class="spinner-border spinner-border-sm me-2"
                                    ></span>

                                    Enregistrement...

                                </span>

                            </button>

                        </form>

                    </div>

                </div>

            </div>


            {{-- ====================================== --}}
            {{-- RÉSUMÉ FINANCIER --}}
            {{-- ====================================== --}}

            <div class="col-lg-4">

                <div class="card card-admin border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="bi bi-receipt text-danger me-2"></i>

                            Résumé de la commande

                        </h5>


                        {{-- SOUS-TOTAL --}}

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Sous-total
                            </span>

                            <strong>

                                {{ number_format(
                                    $order->subtotal,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                FCFA

                            </strong>

                        </div>


                        {{-- LIVRAISON --}}

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Livraison
                            </span>

                            <strong>

                                @if($order->delivery_fee > 0)

                                    {{ number_format(
                                        $order->delivery_fee,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                @else

                                    Gratuit

                                @endif

                            </strong>

                        </div>


                        <hr>


                        {{-- TOTAL --}}

                        <div
                            class="d-flex justify-content-between align-items-center"
                        >

                            <span class="fw-bold">
                                Total
                            </span>

                            <strong class="fs-4 text-danger">

                                {{ number_format(
                                    $order->total,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                FCFA

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================== --}}
            {{-- PRODUITS COMMANDÉS --}}
            {{-- ====================================== --}}

            <div class="col-12">

                <div class="card card-admin border-0 shadow-sm">

                    <div class="card-body p-0">


                        {{-- TITRE --}}

                        <div class="p-4 border-bottom">

                            <div
                                class="d-flex justify-content-between align-items-center"
                            >

                                <div>

                                    <h5 class="fw-bold mb-1">
                                        Produits commandés
                                    </h5>

                                    <small class="text-muted">

                                        {{ $order->items->count() }}

                                        produit(s)

                                    </small>

                                </div>


                                <i class="bi bi-bag-check fs-3 text-danger"></i>

                            </div>

                        </div>


                        {{-- TABLE --}}

                        <div class="table-responsive">

                            <table class="table align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="px-4">
                                            Produit
                                        </th>

                                        <th>
                                            Prix unitaire
                                        </th>

                                        <th>
                                            Quantité
                                        </th>

                                        <th class="text-end px-4">
                                            Sous-total
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($order->items as $item)

                                        <tr>

                                            {{-- PRODUIT --}}

                                            <td class="px-4">

                                                <div
                                                    class="d-flex align-items-center gap-3"
                                                >

                                                    <div
                                                        class="rounded d-flex align-items-center justify-content-center"
                                                        style="
                                                            width:50px;
                                                            height:50px;
                                                            background:#f5f5f5;
                                                        "
                                                    >

                                                        <i
                                                            class="bi bi-box text-muted"
                                                        ></i>

                                                    </div>


                                                    <div>

                                                        <div class="fw-semibold">

                                                            {{ $item->product_name }}

                                                        </div>


                                                        <small class="text-muted">

                                                            Produit
                                                            #{{ $item->product_id }}

                                                        </small>

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- PRIX --}}

                                            <td>

                                                {{ number_format(
                                                    $item->price,
                                                    0,
                                                    ',',
                                                    ' '
                                                ) }}

                                                FCFA

                                            </td>


                                            {{-- QUANTITÉ --}}

                                            <td>

                                                <span
                                                    class="badge bg-light text-dark border"
                                                >

                                                    × {{ $item->quantity }}

                                                </span>

                                            </td>


                                            {{-- SOUS-TOTAL --}}

                                            <td
                                                class="text-end px-4 fw-bold"
                                            >

                                                {{ number_format(
                                                    $item->subtotal,
                                                    0,
                                                    ',',
                                                    ' '
                                                ) }}

                                                FCFA

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="4"
                                                class="text-center py-5 text-muted"
                                            >

                                                <i
                                                    class="bi bi-inbox fs-2 d-block mb-2"
                                                ></i>

                                                Aucun produit dans cette commande.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================== --}}
            {{-- NOTE DU CLIENT --}}
            {{-- ====================================== --}}

            @if($order->customer_note)

                <div class="col-12">

                    <div class="card card-admin border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h5 class="fw-bold mb-3">

                                <i
                                    class="bi bi-chat-left-text text-danger me-2"
                                ></i>

                                Note du client

                            </h5>


                            <div
                                class="p-3 rounded"
                                style="background:#f8f8f8;"
                            >

                                {{ $order->customer_note }}

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ====================================== --}}
            {{-- INFORMATIONS TECHNIQUES --}}
            {{-- ====================================== --}}

            <div class="col-12">

                <div class="card card-admin border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Numéro de commande
                                </small>

                                <strong>
                                    {{ $order->order_number }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    ID commande
                                </small>

                                <strong>
                                    #{{ $order->id }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Dernière modification
                                </small>

                                <strong>

                                    {{ $order->updated_at->format('d/m/Y à H:i') }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>