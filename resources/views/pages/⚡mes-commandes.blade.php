<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Mes commandes')] class extends Component
{
    public function getOrdersProperty()
    {
        return Order::query()
            ->where('user_id', Auth::id())
            ->withCount('items')
            ->latest()
            ->get();
    }

    public function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'processing' => 'En préparation',
            'shipping' => 'En livraison',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            default => ucfirst($status),
        };
    }

    public function statusClass(string $status): string
    {
        return match ($status) {
            'pending' => 'status-pending',
            'confirmed' => 'status-confirmed',
            'processing' => 'status-processing',
            'shipping' => 'status-shipping',
            'delivered' => 'status-delivered',
            'cancelled' => 'status-cancelled',
            default => 'status-default',
        };
    }

    public function paymentLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Paiement en attente',
            'paid' => 'Paiement effectué',
            'failed' => 'Paiement échoué',
            default => ucfirst($status),
        };
    }
};
?>

<div class="ak-orders-page">

    {{-- HEADER --}}
    <section class="orders-hero">

        <div class="container">

            <div class="orders-hero-content">

                <div>
                    <span class="orders-eyebrow">
                        MON COMPTE
                    </span>

                    <h1>
                        Mes commandes
                    </h1>

                    <p>
                        Retrouvez l'historique de vos achats et
                        suivez l'état de vos commandes Akatsuki.
                    </p>
                </div>

                <div class="orders-hero-icon">
                    <i class="bi bi-bag-check"></i>
                </div>

            </div>

        </div>

    </section>


    {{-- CONTENT --}}
    <main class="container py-5">

        {{-- RETOUR PROFIL --}}
        <div class="mb-4">

            <a
                href="{{ route('profile.edit') }}"
                class="orders-back"
            >
                <i class="bi bi-arrow-left"></i>
                Retour à mon profil
            </a>

        </div>


        @if($this->orders->count())

            {{-- STATISTIQUES --}}
            <div class="orders-stats mb-4">

                <div class="order-stat-card">

                    <div class="order-stat-icon">
                        <i class="bi bi-bag"></i>
                    </div>

                    <div>
                        <span>Total commandes</span>

                        <strong>
                            {{ $this->orders->count() }}
                        </strong>
                    </div>

                </div>


                <div class="order-stat-card">

                    <div class="order-stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>
                        <span>En cours</span>

                        <strong>
                            {{ $this->orders->whereNotIn('status', ['delivered', 'cancelled'])->count() }}
                        </strong>
                    </div>

                </div>


                <div class="order-stat-card">

                    <div class="order-stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div>
                        <span>Livrées</span>

                        <strong>
                            {{ $this->orders->where('status', 'delivered')->count() }}
                        </strong>
                    </div>

                </div>

            </div>


            {{-- LISTE COMMANDES --}}
            <div class="orders-list">

                @foreach($this->orders as $order)

                    <article
                        class="order-card"
                        wire:key="order-{{ $order->id }}"
                    >

                        {{-- HEADER COMMANDE --}}
                        <div class="order-card-header">

                            <div>

                                <span class="order-number-label">
                                    COMMANDE
                                </span>

                                <h2>
                                    #{{ $order->order_number }}
                                </h2>

                                <p>
                                    <i class="bi bi-calendar3"></i>

                                    {{ $order->created_at->format('d/m/Y à H:i') }}
                                </p>

                            </div>


                            <span class="order-status {{ $this->statusClass($order->status) }}">

                                @switch($order->status)

                                    @case('pending')
                                        <i class="bi bi-clock"></i>
                                        @break

                                    @case('confirmed')
                                        <i class="bi bi-check"></i>
                                        @break

                                    @case('processing')
                                        <i class="bi bi-box-seam"></i>
                                        @break

                                    @case('shipping')
                                        <i class="bi bi-truck"></i>
                                        @break

                                    @case('delivered')
                                        <i class="bi bi-check-circle"></i>
                                        @break

                                    @case('cancelled')
                                        <i class="bi bi-x-circle"></i>
                                        @break

                                    @default
                                        <i class="bi bi-info-circle"></i>

                                @endswitch

                                {{ $this->statusLabel($order->status) }}

                            </span>

                        </div>


                        {{-- INFOS --}}
                        <div class="order-card-body">

                            <div class="order-info">

                                <div class="order-info-item">

                                    <i class="bi bi-box"></i>

                                    <div>
                                        <span>Articles</span>

                                        <strong>
                                            {{ $order->items_count }}
                                            {{ $order->items_count > 1 ? 'articles' : 'article' }}
                                        </strong>
                                    </div>

                                </div>


                                <div class="order-info-item">

                                    <i class="bi bi-truck"></i>

                                    <div>
                                        <span>Livraison</span>

                                        <strong>
                                            @if($order->delivery_method === 'home_delivery')
                                                À domicile
                                            @else
                                                Retrait
                                            @endif
                                        </strong>
                                    </div>

                                </div>


                                <div class="order-info-item">

                                    <i class="bi bi-credit-card"></i>

                                    <div>
                                        <span>Paiement</span>

                                        <strong>
                                            {{ $this->paymentLabel($order->payment_status) }}
                                        </strong>
                                    </div>

                                </div>


                                <div class="order-info-item">

                                    <i class="bi bi-geo-alt"></i>

                                    <div>
                                        <span>Destination</span>

                                        <strong>
                                            {{ $order->city }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- FOOTER --}}
                        <div class="order-card-footer">

                            <div>

                                <span class="order-total-label">
                                    Total de la commande
                                </span>

                                <strong class="order-total">

                                    {{ number_format(
                                        $order->total,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </strong>

                            </div>


                            <a
                                href="{{ route('commande.detail', $order) }}"
                                class="btn-order-detail"
                            >
                                Voir les détails

                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            {{-- AUCUNE COMMANDE --}}
            <div class="orders-empty">

                <div class="orders-empty-icon">
                    <i class="bi bi-bag-x"></i>
                </div>

                <h2>
                    Vous n'avez pas encore passé de commande
                </h2>

                <p>
                    Découvrez notre catalogue et trouvez les produits
                    qui vous correspondent.
                </p>

                <a
                    href="{{ route('catalogue') }}"
                    class="btn-ak-primary"
                >
                    <i class="bi bi-shop"></i>
                    Découvrir la boutique
                </a>

            </div>

        @endif

    </main>

</div>


<style>

.ak-orders-page {
    background: #f7f7f7;
    min-height: 100vh;
}


/* HERO */

.orders-hero {
    background: #080808;
    color: #fff;
    padding: 55px 0;
}

.orders-hero-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.orders-eyebrow {
    display: block;
    color: #e50914;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 10px;
}

.orders-hero h1 {
    font-size: 42px;
    font-weight: 800;
    margin: 0 0 10px;
}

.orders-hero p {
    margin: 0;
    color: #bbb;
    max-width: 600px;
}

.orders-hero-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e50914;
    font-size: 32px;
}


/* RETOUR */

.orders-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #555;
    text-decoration: none;
    font-weight: 600;
}

.orders-back:hover {
    color: #e50914;
}


/* STATISTIQUES */

.orders-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.order-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,.05);
}

.order-stat-icon {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    border-radius: 14px;
    background: #f1f1f1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.order-stat-card span {
    display: block;
    color: #777;
    font-size: 13px;
    margin-bottom: 4px;
}

.order-stat-card strong {
    font-size: 22px;
}


/* COMMANDE */

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,.05);
}

.order-card-header {
    padding: 25px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    border-bottom: 1px solid #eee;
}

.order-number-label {
    color: #e50914;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.order-card-header h2 {
    margin: 5px 0;
    font-size: 21px;
    font-weight: 800;
}

.order-card-header p {
    margin: 0;
    color: #777;
    font-size: 13px;
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 13px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
}


/* STATUTS */

.status-pending {
    background: #fff4d6;
    color: #9a6b00;
}

.status-confirmed {
    background: #e7f1ff;
    color: #1769aa;
}

.status-processing {
    background: #eee9ff;
    color: #6842c2;
}

.status-shipping {
    background: #e5f7ff;
    color: #087ea4;
}

.status-delivered {
    background: #e6f7ed;
    color: #18864b;
}

.status-cancelled {
    background: #ffe7e7;
    color: #c62828;
}

.status-default {
    background: #eee;
    color: #555;
}


/* CORPS */

.order-card-body {
    padding: 25px;
}

.order-info {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.order-info-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.order-info-item > i {
    font-size: 20px;
    color: #e50914;
}

.order-info-item span {
    display: block;
    color: #888;
    font-size: 12px;
    margin-bottom: 4px;
}

.order-info-item strong {
    font-size: 14px;
}


/* FOOTER */

.order-card-footer {
    padding: 20px 25px;
    background: #fafafa;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.order-total-label {
    display: block;
    color: #888;
    font-size: 12px;
    margin-bottom: 3px;
}

.order-total {
    font-size: 20px;
}


/* BOUTON */

.btn-order-detail {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: #080808;
    color: #fff;
    padding: 12px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: .2s ease;
}

.btn-order-detail:hover {
    background: #e50914;
    color: #fff;
}


/* VIDE */

.orders-empty {
    background: #fff;
    border-radius: 20px;
    padding: 70px 25px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,.05);
}

.orders-empty-icon {
    width: 90px;
    height: 90px;
    margin: 0 auto 25px;
    border-radius: 50%;
    background: #f1f1f1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
    color: #777;
}

.orders-empty h2 {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 10px;
}

.orders-empty p {
    color: #777;
    max-width: 500px;
    margin: 0 auto 25px;
}


/* BOUTON AKATSUKI */

.btn-ak-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    background: #e50914;
    color: #fff;
    border: 0;
    border-radius: 30px;
    padding: 13px 24px;
    text-decoration: none;
    font-weight: 700;
    transition: .2s ease;
}

.btn-ak-primary:hover {
    background: #b20710;
    color: #fff;
}


/* RESPONSIVE */

@media (max-width: 991px) {

    .orders-stats {
        grid-template-columns: 1fr;
    }

    .order-info {
        grid-template-columns: repeat(2, 1fr);
    }

}


@media (max-width: 767px) {

    .orders-hero {
        padding: 40px 0;
    }

    .orders-hero-content {
        align-items: flex-start;
    }

    .orders-hero h1 {
        font-size: 32px;
    }

    .orders-hero-icon {
        width: 60px;
        height: 60px;
        font-size: 25px;
    }

    .order-card-header {
        flex-direction: column;
    }

    .order-info {
        grid-template-columns: 1fr;
    }

    .order-card-footer {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-order-detail {
        justify-content: center;
    }

}

</style>

