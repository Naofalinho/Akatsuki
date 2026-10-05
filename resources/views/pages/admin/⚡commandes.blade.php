<?php

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.admin')] class extends Component
{
    public string $search = '';
    public string $status = 'all';

    public function getOrdersProperty()
    {
        return Order::query()
            ->with('items')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->get();
    }
};
?>

<div class="admin-content">
    @section('title', 'Admin -Commandes ')

    {{-- En-tête --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="page-title mb-1">
                Commandes
            </h2>

            <p class="text-muted mb-0">
                Consultez et gérez les commandes de vos clients.
            </p>
        </div>

    </div>


    {{-- Message --}}
    @if (session()->has('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    @endif


    {{-- Statistiques --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-md-6 col-xl-3">

            <div class="card-admin stat">

                <div class="stat-icon">
                    <i class="bi bi-bag"></i>
                </div>

                <div>
                    <span>
                        Total commandes
                    </span>

                    <strong>
                        {{ Order::count() }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- En attente --}}
        <div class="col-md-6 col-xl-3">

            <div class="card-admin stat">

                <div class="stat-icon">
                    <i class="bi bi-clock"></i>
                </div>

                <div>
                    <span>
                        En attente
                    </span>

                    <strong>
                        {{ Order::where('status', 'pending')->count() }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- En livraison --}}
        <div class="col-md-6 col-xl-3">

            <div class="card-admin stat">

                <div class="stat-icon">
                    <i class="bi bi-truck"></i>
                </div>

                <div>
                    <span>
                        En livraison
                    </span>

                    <strong>
                        {{ Order::whereIn('status', ['processing', 'shipping'])->count() }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- Livrées --}}
        <div class="col-md-6 col-xl-3">

            <div class="card-admin stat">

                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <span>
                        Livrées
                    </span>

                    <strong>
                        {{ Order::where('status', 'delivered')->count() }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- Filtres --}}
    <div class="card-admin mb-4">

        <div class="row g-3 align-items-end">

            <div class="col-md-8">

                <label class="form-label">
                    Rechercher
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>

                    <input type="text" class="form-control" wire:model.live="search"
                        placeholder="Numéro, client, email ou téléphone...">

                </div>

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Statut
                </label>

                <select class="form-select" wire:model.live="status">

                    <option value="all">
                        Toutes les commandes
                    </option>

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

            </div>

        </div>

    </div>


    {{-- Tableau --}}
    <div class="card-admin">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>

                        <th>
                            Commande
                        </th>

                        <th>
                            Client
                        </th>

                        <th>
                            Livraison
                        </th>

                        <th>
                            Montant
                        </th>

                        <th>
                            Paiement
                        </th>

                        <th>
                            Statut
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($this->orders as $order)

                    <tr>

                        {{-- Numéro --}}
                        <td>

                            <strong>
                                {{ $order->order_number }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $order->created_at?->format('d/m/Y H:i') }}
                            </small>

                        </td>


                        {{-- Client --}}
                        <td>

                            <strong>
                                {{ $order->customer_name }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $order->email }}
                            </small>

                            <br>

                            <small class="text-muted">
                                {{ $order->phone }}
                            </small>

                        </td>


                        {{-- Livraison --}}
                        <td>

                            @if ($order->delivery_method === 'home_delivery')

                            <span>
                                Livraison à domicile
                            </span>

                            <br>

                            <small class="text-muted">
                                {{ $order->city }},
                                {{ $order->neighborhood }}
                            </small>

                            @else

                            <span>
                                Retrait
                            </span>

                            <br>

                            <small class="text-muted">
                                Point de retrait
                            </small>

                            @endif

                        </td>


                        {{-- Montant --}}
                        <td>

                            <strong>
                                {{ number_format((float) $order->total, 0, ',', ' ') }}
                                FCFA
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $order->items->sum('quantity') }}
                                article(s)
                            </small>

                        </td>


                        {{-- Paiement --}}
                        <td>

                            @switch($order->payment_status)

                            @case('paid')

                            <span class="order-status order-delivered">
                                <i class="bi bi-check-circle me-1"></i>
                                Payé
                            </span>

                            @break

                            @case('failed')

                            <span class="order-status order-cancelled">
                                <i class="bi bi-x-circle me-1"></i>
                                Échec
                            </span>

                            @break

                            @default

                            <span class="order-status order-pending">
                                <i class="bi bi-clock me-1"></i>
                                En attente
                            </span>

                            @endswitch

                        </td>


                        {{-- Statut commande --}}
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

                            <span class="order-status order-shipped">
                                Préparation
                            </span>

                            @break

                            @case('shipping')

                            <span class="order-status order-shipped">
                                Livraison
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

                            @endswitch

                        </td>


                        {{-- Actions --}}
                        <td class="text-end">

                            <a href="{{ route('admin.commandes.show', $order->id) }}"
                                class="btn btn-sm btn-outline-secondary" title="Voir la commande">
                                <i class="bi bi-eye"></i>
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="text-center py-5">

                            <div class="empty-products">

                                <i class="bi bi-bag-x"></i>

                                <h5>
                                    Aucune commande
                                </h5>

                                <p class="text-muted mb-0">
                                    Aucune commande n'a encore été enregistrée.
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