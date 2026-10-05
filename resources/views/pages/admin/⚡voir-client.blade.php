<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.admin')]
#[Title('Détails du client')]
class extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load([
            'orders' => function ($query) {
                $query->latest();
            },
        ]);
    }
};
?>

<div>
    @section('title', 'Admin - Voir le client')
   

    <div class="container-fluid px-4 py-4">

        {{-- ========================================== --}}
        {{-- EN-TÊTE --}}
        {{-- ========================================== --}}

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4"
        >

            <div>

                <div class="d-flex align-items-center gap-2 mb-2">

                    <a
                        href="{{ route('admin.clients') }}"
                        class="text-decoration-none text-muted"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Clients
                    </a>

                    <span class="text-muted">
                        /
                    </span>

                    <span class="text-muted">
                        {{ $user->name }}
                    </span>

                </div>


                <h2 class="fw-bold mb-1">
                    {{ $user->name }}
                </h2>


                <p class="text-muted mb-0">
                    Client depuis
                    {{ $user->created_at?->format('d/m/Y') }}
                </p>

            </div>


            <div>

                <a
                    href="{{ route('admin.clients') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Retour aux clients
                </a>

            </div>

        </div>


        {{-- ========================================== --}}
        {{-- INFORMATIONS PRINCIPALES --}}
        {{-- ========================================== --}}

        <div class="row g-4">


            {{-- ====================================== --}}
            {{-- PROFIL CLIENT --}}
            {{-- ====================================== --}}

            <div class="col-lg-5">

                <div class="card card-admin border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        {{-- AVATAR --}}

                        <div class="text-center mb-4">

                            <div
                                class="rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold"
                                style="
                                    width:90px;
                                    height:90px;
                                    background:#fde8e8;
                                    color:#e50914;
                                    font-size:36px;
                                "
                            >

                                {{ strtoupper(
                                    substr($user->name ?? 'C', 0, 1)
                                ) }}

                            </div>


                            <h4 class="fw-bold mt-3 mb-1">

                                {{ $user->name }}

                            </h4>


                            <p class="text-muted mb-0">

                                {{ $user->email }}

                            </p>

                        </div>


                        <hr>


                        {{-- EMAIL --}}

                        <div class="mb-4">

                            <small class="text-muted d-block mb-1">
                                Adresse email
                            </small>

                            <div class="d-flex align-items-center gap-2">

                                <i class="bi bi-envelope text-danger"></i>

                                <a
                                    href="mailto:{{ $user->email }}"
                                    class="text-decoration-none"
                                >
                                    {{ $user->email }}
                                </a>

                            </div>

                        </div>


                        {{-- TELEPHONE --}}

                        <div class="mb-4">

                            <small class="text-muted d-block mb-1">
                                Téléphone
                            </small>

                            <div class="d-flex align-items-center gap-2">

                                <i class="bi bi-telephone text-danger"></i>

                                @if($user->phone)

                                    <a
                                        href="tel:{{ $user->phone }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $user->phone }}
                                    </a>

                                @else

                                    <span class="text-muted">
                                        Non renseigné
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- DATE INSCRIPTION --}}

                        <div class="mb-4">

                            <small class="text-muted d-block mb-1">
                                Date d'inscription
                            </small>

                            <div class="d-flex align-items-center gap-2">

                                <i class="bi bi-calendar3 text-danger"></i>

                                <span>

                                    {{ $user->created_at?->format('d/m/Y à H:i') }}

                                </span>

                            </div>

                        </div>


                        {{-- STATUT EMAIL --}}

                        <div>

                            <small class="text-muted d-block mb-2">
                                Vérification du compte
                            </small>


                            @if($user->email_verified_at)

                                <span class="badge bg-success">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Email vérifié

                                </span>

                            @else

                                <span class="badge bg-warning text-dark">

                                    <i class="bi bi-clock me-1"></i>

                                    Email non vérifié

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================== --}}
            {{-- STATISTIQUES --}}
            {{-- ====================================== --}}

            <div class="col-lg-7">

                <div class="row g-4">


                    {{-- NOMBRE COMMANDES --}}

                    <div class="col-md-6">

                        <div class="card card-admin border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <div
                                    class="d-flex align-items-center justify-content-between"
                                >

                                    <div>

                                        <small class="text-muted d-block mb-2">
                                            Commandes
                                        </small>

                                        <h2 class="fw-bold mb-0">

                                            {{ $user->orders->count() }}

                                        </h2>

                                    </div>


                                    <div
                                        class="rounded d-flex align-items-center justify-content-center"
                                        style="
                                            width:50px;
                                            height:50px;
                                            background:#fde8e8;
                                            color:#e50914;
                                        "
                                    >

                                        <i class="bi bi-bag-check fs-4"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- TOTAL DEPENSE --}}

                    <div class="col-md-6">

                        <div class="card card-admin border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <div
                                    class="d-flex align-items-center justify-content-between"
                                >

                                    <div>

                                        <small class="text-muted d-block mb-2">
                                            Total dépensé
                                        </small>

                                        <h4 class="fw-bold mb-0 text-danger">

                                            {{ number_format(
                                                $user->orders->where(
                                                    'status',
                                                    '!=',
                                                    'cancelled'
                                                )->sum('total'),
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </h4>

                                    </div>


                                    <div
                                        class="rounded d-flex align-items-center justify-content-center"
                                        style="
                                            width:50px;
                                            height:50px;
                                            background:#fde8e8;
                                            color:#e50914;
                                        "
                                    >

                                        <i class="bi bi-cash-stack fs-4"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- COMMANDES EN ATTENTE --}}

                    <div class="col-md-6">

                        <div class="card card-admin border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <small class="text-muted d-block mb-2">
                                    Commandes en attente
                                </small>

                                <h2 class="fw-bold mb-0">

                                    {{ $user->orders->where(
                                        'status',
                                        'pending'
                                    )->count() }}

                                </h2>

                            </div>

                        </div>

                    </div>


                    {{-- COMMANDES LIVRÉES --}}

                    <div class="col-md-6">

                        <div class="card card-admin border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <small class="text-muted d-block mb-2">
                                    Commandes livrées
                                </small>

                                <h2 class="fw-bold mb-0">

                                    {{ $user->orders->where(
                                        'status',
                                        'delivered'
                                    )->count() }}

                                </h2>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================== --}}
            {{-- HISTORIQUE DES COMMANDES --}}
            {{-- ========================================== --}}

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
                                        Historique des commandes
                                    </h5>

                                    <small class="text-muted">
                                        Commandes effectuées par ce client
                                    </small>

                                </div>


                                <i class="bi bi-clock-history fs-3 text-danger"></i>

                            </div>

                        </div>


                        {{-- TABLE --}}

                        <div class="table-responsive">

                            <table class="table align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="px-4">
                                            Commande
                                        </th>

                                        <th>
                                            Date
                                        </th>

                                        <th>
                                            Total
                                        </th>

                                        <th>
                                            Paiement
                                        </th>

                                        <th>
                                            Statut
                                        </th>

                                        <th class="text-end px-4">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($user->orders as $order)

                                        <tr>

                                            {{-- NUMÉRO --}}

                                            <td class="px-4">

                                                <strong>
                                                    {{ $order->order_number }}
                                                </strong>

                                            </td>


                                            {{-- DATE --}}

                                            <td>

                                                <span>
                                                    {{ $order->created_at->format('d/m/Y') }}
                                                </span>

                                                <small class="text-muted d-block">
                                                    {{ $order->created_at->format('H:i') }}
                                                </small>

                                            </td>


                                            {{-- TOTAL --}}

                                            <td>

                                                <strong>

                                                    {{ number_format(
                                                        $order->total,
                                                        0,
                                                        ',',
                                                        ' '
                                                    ) }}

                                                    FCFA

                                                </strong>

                                            </td>


                                            {{-- PAIEMENT --}}

                                            <td>

                                                @switch($order->payment_status)

                                                    @case('paid')

                                                        <span class="badge bg-success">
                                                            Payé
                                                        </span>

                                                        @break

                                                    @case('failed')

                                                        <span class="badge bg-danger">
                                                            Échec
                                                        </span>

                                                        @break

                                                    @default

                                                        <span class="badge bg-warning text-dark">
                                                            En attente
                                                        </span>

                                                @endswitch

                                            </td>


                                            {{-- STATUT --}}

                                            <td>

                                                @switch($order->status)

                                                    @case('pending')

                                                        <span class="order-status order-pending">
                                                            En attente
                                                        </span>

                                                        @break

                                                    @case('confirmed')

                                                        <span class="order-status order-confirmed">
                                                            Confirmée
                                                        </span>

                                                        @break

                                                    @case('processing')

                                                        <span class="order-status order-confirmed">
                                                            En préparation
                                                        </span>

                                                        @break

                                                    @case('shipping')

                                                        <span class="order-status order-shipped">
                                                            En livraison
                                                        </span>

                                                        @break

                                                    @case('delivered')

                                                        <span class="order-status order-delivered">
                                                            Livrée
                                                        </span>

                                                        @break

                                                    @case('cancelled')

                                                        <span class="order-status order-cancelled">
                                                            Annulée
                                                        </span>

                                                        @break

                                                    @default

                                                        <span class="order-status">
                                                            {{ $order->status }}
                                                        </span>

                                                @endswitch

                                            </td>


                                            {{-- ACTION --}}

                                            <td class="text-end px-4">

                                                <a
                                                    href="{{ route(
                                                        'admin.commandes.show',
                                                        $order->id
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Voir la commande"
                                                >

                                                    <i class="bi bi-eye"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="6"
                                                class="text-center py-5"
                                            >

                                                <i
                                                    class="bi bi-bag-x fs-1 text-muted d-block mb-3"
                                                ></i>

                                                <h5 class="fw-bold">
                                                    Aucune commande
                                                </h5>

                                                <p class="text-muted mb-0">
                                                    Ce client n'a encore effectué aucune commande.
                                                </p>

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================== --}}
            {{-- INFORMATIONS COMPTE --}}
            {{-- ========================================== --}}

            <div class="col-12">

                <div class="card card-admin border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="bi bi-person-vcard text-danger me-2"></i>

                            Informations du compte

                        </h5>


                        <div class="row g-4">

                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    Identifiant utilisateur
                                </small>

                                <strong>
                                    #{{ $user->id }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    Compte créé le
                                </small>

                                <strong>

                                    {{ $user->created_at?->format(
                                        'd/m/Y à H:i'
                                    ) }}

                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    Dernière modification
                                </small>

                                <strong>

                                    {{ $user->updated_at?->format(
                                        'd/m/Y à H:i'
                                    ) }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>