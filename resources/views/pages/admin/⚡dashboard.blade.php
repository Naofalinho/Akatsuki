<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.admin')]
#[Title('Tableau de bord')]
class extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Statistiques produits
    |--------------------------------------------------------------------------
    */

    public function getTotalProductsProperty(): int
    {
        return Product::count();
    }

    public function getActiveProductsProperty(): int
    {
        return Product::where('is_active', true)->count();
    }

    public function getLowStockProductsProperty(): int
    {
        return Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->count();
    }

    public function getOutOfStockProductsProperty(): int
    {
        return Product::where('stock', '<=', 0)->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Statistiques commandes
    |--------------------------------------------------------------------------
    */

    public function getTotalOrdersProperty(): int
    {
        return Order::count();
    }

    public function getPendingOrdersProperty(): int
    {
        return Order::where('status', 'pending')->count();
    }

    public function getProcessingOrdersProperty(): int
    {
        return Order::whereIn('status', [
            'confirmed',
            'processing',
            'shipping',
        ])->count();
    }

    public function getDeliveredOrdersProperty(): int
    {
        return Order::where('status', 'delivered')->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Chiffre d'affaires
    |--------------------------------------------------------------------------
    */

    public function getTotalSalesProperty(): float
    {
        return (float) Order::query()
            ->where('status', '!=', 'cancelled')
            ->sum('total');
    }

    /*
    |--------------------------------------------------------------------------
    | Clients
    |--------------------------------------------------------------------------
    */

    public function getTotalCustomersProperty(): int
    {
        return User::count();
    }

    /*
    |--------------------------------------------------------------------------
    | Commandes récentes
    |--------------------------------------------------------------------------
    */

    public function getRecentOrdersProperty()
    {
        return Order::query()
            ->with('items')
            ->latest()
            ->limit(8)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Produits les plus vendus
    |--------------------------------------------------------------------------
    */

    public function getBestSellingProductsProperty()
    {
        return OrderItem::query()
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_sales')
            )
            ->whereHas('order', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->groupBy(
                'product_id',
                'product_name'
            )
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Pourcentage commandes livrées
    |--------------------------------------------------------------------------
    */

    public function getDeliveredPercentageProperty(): int
    {
        if ($this->totalOrders === 0) {
            return 0;
        }

        return (int) round(
            ($this->deliveredOrders / $this->totalOrders) * 100
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('pages.admin.⚡dashboard');
    }
};
?>

<div class="admin-dashboard">
    @section('title', 'Admin - Dashboard ')
    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="dashboard-header">

        <div>
            <span class="dashboard-eyebrow">
                ADMINISTRATION
            </span>

            <h1 class="dashboard-title">
                Tableau de bord
            </h1>

            <p class="dashboard-subtitle">
                Vue générale de votre boutique AKATSUKI.
            </p>
        </div>

        <div class="dashboard-header-actions">

            <a href="{{ route('admin.produits.create') }}"
               class="dashboard-action-btn">
                <i class="bi bi-plus-lg"></i>
                Ajouter un produit
            </a>

            <a href="{{ route('admin.commandes') }}"
               class="dashboard-secondary-btn">
                <i class="bi bi-bag"></i>
                Voir les commandes
            </a>

        </div>

    </div>


    {{-- =========================================================
         STATISTIQUES PRINCIPALES
    ========================================================== --}}

    <div class="dashboard-stats-grid">

        {{-- Produits --}}

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon products">
                <i class="bi bi-box-seam"></i>
            </div>

            <div class="dashboard-stat-content">

                <span class="dashboard-stat-label">
                    Produits
                </span>

                <strong class="dashboard-stat-value">
                    {{ number_format($this->totalProducts, 0, ',', ' ') }}
                </strong>

                <span class="dashboard-stat-description">
                    {{ $this->activeProducts }} produits actifs
                </span>

            </div>

        </div>


        {{-- Commandes --}}

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon orders">
                <i class="bi bi-bag-check"></i>
            </div>

            <div class="dashboard-stat-content">

                <span class="dashboard-stat-label">
                    Commandes
                </span>

                <strong class="dashboard-stat-value">
                    {{ number_format($this->totalOrders, 0, ',', ' ') }}
                </strong>

                <span class="dashboard-stat-description">
                    {{ $this->pendingOrders }} en attente
                </span>

            </div>

        </div>


        {{-- Chiffre d'affaires --}}

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon sales">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <div class="dashboard-stat-content">

                <span class="dashboard-stat-label">
                    Chiffre d'affaires
                </span>

                <strong class="dashboard-stat-value dashboard-money">
                    {{ number_format($this->totalSales, 0, ',', ' ') }}
                    <small>FCFA</small>
                </strong>

                <span class="dashboard-stat-description">
                    Commandes non annulées
                </span>

            </div>

        </div>


        {{-- Clients --}}

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon customers">
                <i class="bi bi-people"></i>
            </div>

            <div class="dashboard-stat-content">

                <span class="dashboard-stat-label">
                    Clients
                </span>

                <strong class="dashboard-stat-value">
                    {{ number_format($this->totalCustomers, 0, ',', ' ') }}
                </strong>

                <span class="dashboard-stat-description">
                    Comptes enregistrés
                </span>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DEUXIÈME LIGNE DE STATISTIQUES
    ========================================================== --}}

    <div class="dashboard-secondary-grid">

        {{-- Commandes en attente --}}

        <div class="dashboard-mini-card">

            <div class="dashboard-mini-icon pending">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div>
                <span>En attente</span>

                <strong>
                    {{ $this->pendingOrders }}
                </strong>
            </div>

        </div>


        {{-- Commandes en traitement --}}

        <div class="dashboard-mini-card">

            <div class="dashboard-mini-icon processing">
                <i class="bi bi-arrow-repeat"></i>
            </div>

            <div>
                <span>En traitement</span>

                <strong>
                    {{ $this->processingOrders }}
                </strong>
            </div>

        </div>


        {{-- Commandes livrées --}}

        <div class="dashboard-mini-card">

            <div class="dashboard-mini-icon delivered">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>
                <span>Livrées</span>

                <strong>
                    {{ $this->deliveredOrders }}
                </strong>
            </div>

        </div>


        {{-- Stock faible --}}

        <div class="dashboard-mini-card">

            <div class="dashboard-mini-icon low-stock">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div>
                <span>Stock faible</span>

                <strong>
                    {{ $this->lowStockProducts }}
                </strong>
            </div>

        </div>


        {{-- Rupture --}}

        <div class="dashboard-mini-card">

            <div class="dashboard-mini-icon out-stock">
                <i class="bi bi-x-circle"></i>
            </div>

            <div>
                <span>Rupture</span>

                <strong>
                    {{ $this->outOfStockProducts }}
                </strong>
            </div>

        </div>

    </div>


    {{-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== --}}

    <div class="dashboard-content-grid">


        {{-- =====================================================
             COMMANDES RÉCENTES
        ====================================================== --}}

        <div class="dashboard-panel dashboard-orders-panel">

            <div class="dashboard-panel-header">

                <div>
                    <h2>
                        Commandes récentes
                    </h2>

                    <p>
                        Les dernières commandes de votre boutique.
                    </p>
                </div>

                <a href="{{ route('admin.commandes') }}"
                   class="dashboard-panel-link">
                    Voir tout
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            @if ($this->recentOrders->count())

                <div class="dashboard-orders-list">

                    @foreach ($this->recentOrders as $order)

                        <div class="dashboard-order-row">

                            <div class="dashboard-order-main">

                                <div class="dashboard-order-icon">
                                    <i class="bi bi-bag"></i>
                                </div>

                                <div>

                                    <a href="{{ route('admin.commandes.show', $order->id) }}"
                                       class="dashboard-order-number">
                                        #{{ $order->order_number }}
                                    </a>

                                    <span class="dashboard-order-customer">
                                        {{ $order->customer_name }}
                                    </span>

                                </div>

                            </div>


                            <div class="dashboard-order-info">

                                <span class="dashboard-order-date">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </span>

                                <strong>
                                    {{ number_format($order->total, 0, ',', ' ') }}
                                    FCFA
                                </strong>

                            </div>


                            <div>

                                @php
                                    $statusClasses = [
                                        'pending' => 'status-pending',
                                        'confirmed' => 'status-confirmed',
                                        'processing' => 'status-processing',
                                        'shipping' => 'status-shipping',
                                        'delivered' => 'status-delivered',
                                        'cancelled' => 'status-cancelled',
                                    ];

                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'confirmed' => 'Confirmée',
                                        'processing' => 'En traitement',
                                        'shipping' => 'Expédition',
                                        'delivered' => 'Livrée',
                                        'cancelled' => 'Annulée',
                                    ];
                                @endphp

                                <span class="dashboard-status {{ $statusClasses[$order->status] ?? 'status-pending' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>

                            </div>


                            <a href="{{ route('admin.commandes.show', $order->id) }}"
                               class="dashboard-order-view"
                               title="Voir la commande">

                                <i class="bi bi-chevron-right"></i>

                            </a>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="dashboard-empty">

                    <div class="dashboard-empty-icon">
                        <i class="bi bi-bag-x"></i>
                    </div>

                    <h3>
                        Aucune commande
                    </h3>

                    <p>
                        Les nouvelles commandes apparaîtront ici.
                    </p>

                </div>

            @endif

        </div>


        {{-- =====================================================
             PRODUITS LES PLUS VENDUS
        ====================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>
                    <h2>
                        Produits les plus vendus
                    </h2>

                    <p>
                        Produits ayant généré le plus de ventes.
                    </p>
                </div>

                <a href="{{ route('admin.produits') }}"
                   class="dashboard-panel-link">
                    Produits
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            @if ($this->bestSellingProducts->count())

                <div class="best-products-list">

                    @foreach ($this->bestSellingProducts as $index => $product)

                        <div class="best-product-row">

                            <div class="best-product-rank">
                                {{ $index + 1 }}
                            </div>

                            <div class="best-product-info">

                                <strong>
                                    {{ $product->product_name }}
                                </strong>

                                <span>
                                    {{ $product->total_quantity }}
                                    {{ $product->total_quantity > 1 ? 'unités vendues' : 'unité vendue' }}
                                </span>

                            </div>

                            <strong class="best-product-sales">

                                {{ number_format($product->total_sales, 0, ',', ' ') }}

                                <small>
                                    FCFA
                                </small>

                            </strong>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="dashboard-empty">

                    <div class="dashboard-empty-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>

                    <h3>
                        Pas encore de ventes
                    </h3>

                    <p>
                        Les statistiques apparaîtront après les premières commandes.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         INFORMATIONS STOCK / LIVRAISON
    ========================================================== --}}

    <div class="dashboard-bottom-grid">

        {{-- Stock --}}

        <div class="dashboard-info-panel">

            <div class="dashboard-info-header">

                <div class="dashboard-info-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <h3>
                        État du stock
                    </h3>

                    <p>
                        Surveillez vos produits.
                    </p>
                </div>

            </div>


            <div class="stock-progress">

                <div class="stock-progress-header">

                    <span>
                        Produits disponibles
                    </span>

                    <strong>
                        {{ $this->activeProducts }}/{{ $this->totalProducts }}
                    </strong>

                </div>

                @php
                    $stockPercentage = $this->totalProducts > 0
                        ? ($this->activeProducts / $this->totalProducts) * 100
                        : 0;
                @endphp

                <div class="progress-track">
                    <div
                        class="progress-fill"
                        style="width: {{ min($stockPercentage, 100) }}%"
                    ></div>
                </div>

            </div>


            <div class="dashboard-alerts">

                @if ($this->lowStockProducts > 0)

                    <a href="{{ route('admin.stock') }}"
                       class="dashboard-alert warning">

                        <i class="bi bi-exclamation-triangle"></i>

                        <span>
                            {{ $this->lowStockProducts }}
                            produit(s) avec un stock faible.
                        </span>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                @endif


                @if ($this->outOfStockProducts > 0)

                    <a href="{{ route('admin.stock') }}"
                       class="dashboard-alert danger">

                        <i class="bi bi-x-circle"></i>

                        <span>
                            {{ $this->outOfStockProducts }}
                            produit(s) en rupture.
                        </span>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                @endif


                @if ($this->lowStockProducts === 0 && $this->outOfStockProducts === 0)

                    <div class="dashboard-alert success">

                        <i class="bi bi-check-circle"></i>

                        <span>
                            Tous vos produits ont un stock correct.
                        </span>

                    </div>

                @endif

            </div>

        </div>


        {{-- Livraison --}}

        <div class="dashboard-info-panel">

            <div class="dashboard-info-header">

                <div class="dashboard-info-icon delivery">
                    <i class="bi bi-truck"></i>
                </div>

                <div>
                    <h3>
                        Livraison
                    </h3>

                    <p>
                        Progression des commandes.
                    </p>
                </div>

            </div>


            <div class="delivery-percentage">

                <strong>
                    {{ $this->deliveredPercentage }}%
                </strong>

                <span>
                    des commandes livrées
                </span>

            </div>


            <div class="progress-track large">

                <div
                    class="progress-fill"
                    style="width: {{ $this->deliveredPercentage }}%"
                ></div>

            </div>


            <div class="delivery-summary">

                <div>
                    <span>
                        Total
                    </span>

                    <strong>
                        {{ $this->totalOrders }}
                    </strong>
                </div>

                <div>
                    <span>
                        En traitement
                    </span>

                    <strong>
                        {{ $this->processingOrders }}
                    </strong>
                </div>

                <div>
                    <span>
                        Livrées
                    </span>

                    <strong>
                        {{ $this->deliveredOrders }}
                    </strong>
                </div>

            </div>

        </div>

    </div>

</div>