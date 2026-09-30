<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Détail de la commande')] class extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        /*
        |--------------------------------------------------------------------------
        | Sécurité
        |--------------------------------------------------------------------------
        | Un client ne peut consulter que ses propres commandes.
        */
        abort_unless(
            $order->user_id === Auth::id(),
            404
        );

        $this->order = $order->load([
            'items.product',
        ]);
    }

    public function statusLabel(): string
    {
        return match ($this->order->status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'processing' => 'En préparation',
            'shipping' => 'En livraison',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            default => ucfirst($this->order->status),
        };
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->order->payment_method) {
            'cash_on_delivery' => 'Paiement à la livraison',
            'mtn_momo' => 'MTN Mobile Money',
            'moov_money' => 'Moov Money',
            default => ucfirst($this->order->payment_method),
        };
    }

    public function paymentStatusLabel(): string
    {
        return match ($this->order->payment_status) {
            'pending' => 'En attente',
            'paid' => 'Payé',
            'failed' => 'Échec',
            default => ucfirst($this->order->payment_status),
        };
    }

    public function deliveryMethodLabel(): string
    {
        return match ($this->order->delivery_method) {
            'home_delivery' => 'Livraison à domicile',
            'pickup' => 'Retrait en boutique',
            default => ucfirst($this->order->delivery_method),
        };
    }

    public function statusIcon(string $status): string
    {
        return match ($status) {
            'pending' => 'bi-clock',
            'confirmed' => 'bi-check-circle',
            'processing' => 'bi-box-seam',
            'shipping' => 'bi-truck',
            'delivered' => 'bi-check-circle-fill',
            'cancelled' => 'bi-x-circle-fill',
            default => 'bi-circle',
        };
    }

    public function statusClass(): string
    {
        return match ($this->order->status) {
            'pending' => 'ak-status-pending',
            'confirmed' => 'ak-status-confirmed',
            'processing' => 'ak-status-processing',
            'shipping' => 'ak-status-shipping',
            'delivered' => 'ak-status-delivered',
            'cancelled' => 'ak-status-cancelled',
            default => 'ak-status-default',
        };
    }

    public function isStepActive(string $step): bool
    {
        $steps = [
            'confirmed' => 1,
            'processing' => 2,
            'shipping' => 3,
            'delivered' => 4,
        ];

        if ($this->order->status === 'cancelled') {
            return false;
        }

        if ($this->order->status === 'pending') {
            return false;
        }

        return ($steps[$this->order->status] ?? 0)
            >= ($steps[$step] ?? 0);
    }

    public function isCurrentStep(string $step): bool
    {
        return $this->order->status === $step;
    }
};
?>

<div class="ak-order-detail-page">

    {{-- =========================================================
         HERO
    ========================================================== --}}
    <section class="ak-order-detail-hero">

        <div class="container">

            <a
                href="{{ route('mes-commandes') }}"
                class="ak-order-back"
            >
                <i class="bi bi-arrow-left"></i>
                Retour à mes commandes
            </a>

            <div class="ak-order-hero-content">

                <div>

                    <span class="ak-order-eyebrow">
                        DÉTAIL DE LA COMMANDE
                    </span>

                    <h1>
                        #{{ $order->order_number }}
                    </h1>

                    <p>
                        Commandée le
                        {{ $order->created_at->format('d/m/Y à H:i') }}
                    </p>

                </div>

                <div class="ak-order-hero-status">

                    <span class="ak-order-status {{ $this->statusClass() }}">

                        <i class="bi {{ $this->statusIcon($order->status) }}"></i>

                        {{ $this->statusLabel() }}

                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
         CONTENU
    ========================================================== --}}
    <main class="container ak-order-detail-container">

        {{-- =====================================================
             COMMANDE ANNULÉE
        ====================================================== --}}
        @if($order->status === 'cancelled')

            <div class="ak-order-alert ak-order-alert-danger">

                <div class="ak-order-alert-icon">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div>
                    <strong>Commande annulée</strong>

                    <p>
                        Cette commande a été annulée et ne sera pas
                        livrée.
                    </p>
                </div>

            </div>

        @endif


        {{-- =====================================================
             PROGRESSION
        ====================================================== --}}
        @if($order->status !== 'cancelled')

            <section class="ak-order-card ak-order-tracking">

                <div class="ak-order-card-header">

                    <div>
                        <span class="ak-section-label">
                            SUIVI
                        </span>

                        <h2>
                            Suivi de votre commande
                        </h2>
                    </div>

                    <i class="bi bi-truck"></i>

                </div>


                <div class="ak-order-progress">

                    {{-- CONFIRMÉE --}}
                    <div
                        class="ak-progress-step
                        {{ $this->isStepActive('confirmed') ? 'active' : '' }}
                        {{ $this->isCurrentStep('confirmed') ? 'current' : '' }}"
                    >

                        <div class="ak-progress-icon">
                            <i class="bi bi-check-lg"></i>
                        </div>

                        <div class="ak-progress-text">
                            <strong>Confirmée</strong>
                            <span>Commande validée</span>
                        </div>

                    </div>


                    <div class="ak-progress-line
                        {{ $this->isStepActive('processing') ? 'active' : '' }}">
                    </div>


                    {{-- PRÉPARATION --}}
                    <div
                        class="ak-progress-step
                        {{ $this->isStepActive('processing') ? 'active' : '' }}
                        {{ $this->isCurrentStep('processing') ? 'current' : '' }}"
                    >

                        <div class="ak-progress-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div class="ak-progress-text">
                            <strong>Préparation</strong>
                            <span>Commande préparée</span>
                        </div>

                    </div>


                    <div class="ak-progress-line
                        {{ $this->isStepActive('shipping') ? 'active' : '' }}">
                    </div>


                    {{-- LIVRAISON --}}
                    <div
                        class="ak-progress-step
                        {{ $this->isStepActive('shipping') ? 'active' : '' }}
                        {{ $this->isCurrentStep('shipping') ? 'current' : '' }}"
                    >

                        <div class="ak-progress-icon">
                            <i class="bi bi-truck"></i>
                        </div>

                        <div class="ak-progress-text">
                            <strong>En livraison</strong>
                            <span>Votre colis est en route</span>
                        </div>

                    </div>


                    <div class="ak-progress-line
                        {{ $this->isStepActive('delivered') ? 'active' : '' }}">
                    </div>


                    {{-- LIVRÉE --}}
                    <div
                        class="ak-progress-step
                        {{ $this->isStepActive('delivered') ? 'active' : '' }}
                        {{ $this->isCurrentStep('delivered') ? 'current' : '' }}"
                    >

                        <div class="ak-progress-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>

                        <div class="ak-progress-text">
                            <strong>Livrée</strong>
                            <span>Commande reçue</span>
                        </div>

                    </div>

                </div>

            </section>

        @endif


        <div class="ak-order-layout">

            {{-- =================================================
                 COLONNE PRINCIPALE
            ================================================== --}}
            <div class="ak-order-main">


                {{-- =============================================
                     PRODUITS
                ============================================== --}}
                <section class="ak-order-card">

                    <div class="ak-order-card-header">

                        <div>
                            <span class="ak-section-label">
                                PRODUITS
                            </span>

                            <h2>
                                Articles commandés
                            </h2>
                        </div>

                        <span class="ak-items-count">
                            {{ $order->items->sum('quantity') }}
                            {{ $order->items->sum('quantity') > 1 ? 'articles' : 'article' }}
                        </span>

                    </div>


                    <div class="ak-order-products">

                        @foreach($order->items as $item)

                            <div
                                class="ak-order-product"
                                wire:key="order-item-{{ $item->id }}"
                            >

                                {{-- IMAGE --}}
                                <div class="ak-product-image">

                                    @if($item->product && $item->product->image)

                                        <img
                                            src="{{ asset('storage/' . $item->product->image) }}"
                                            alt="{{ $item->product_name }}"
                                        >

                                    @else

                                        <div class="ak-product-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>

                                    @endif

                                </div>


                                {{-- INFORMATIONS --}}
                                <div class="ak-product-info">

                                    <h3>
                                        {{ $item->product_name }}
                                    </h3>

                                    <p>
                                        Prix unitaire :
                                        <strong>
                                            {{ number_format($item->price, 0, ',', ' ') }}
                                            FCFA
                                        </strong>
                                    </p>

                                    <span class="ak-product-quantity">
                                        Quantité :
                                        {{ $item->quantity }}
                                    </span>

                                </div>


                                {{-- SOUS-TOTAL --}}
                                <div class="ak-product-total">

                                    <span>
                                        Sous-total
                                    </span>

                                    <strong>
                                        {{ number_format($item->subtotal, 0, ',', ' ') }}
                                        FCFA
                                    </strong>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </section>


                {{-- =============================================
                     INFORMATIONS LIVRAISON
                ============================================== --}}
                <section class="ak-order-card">

                    <div class="ak-order-card-header">

                        <div>
                            <span class="ak-section-label">
                                LIVRAISON
                            </span>

                            <h2>
                                Adresse de livraison
                            </h2>
                        </div>

                        <i class="bi bi-geo-alt"></i>

                    </div>


                    <div class="ak-delivery-content">

                        <div class="ak-delivery-type">

                            <div class="ak-delivery-icon">
                                <i class="bi bi-house-door"></i>
                            </div>

                            <div>

                                <strong>
                                    {{ $this->deliveryMethodLabel() }}
                                </strong>

                                <span>
                                    {{ $order->country }}
                                </span>

                            </div>

                        </div>


                        <div class="ak-address-box">

                            <div class="ak-address-row">

                                <i class="bi bi-person"></i>

                                <div>
                                    <span>Destinataire</span>
                                    <strong>
                                        {{ $order->customer_name }}
                                    </strong>
                                </div>

                            </div>


                            <div class="ak-address-row">

                                <i class="bi bi-telephone"></i>

                                <div>
                                    <span>Téléphone</span>
                                    <strong>
                                        {{ $order->phone }}
                                    </strong>
                                </div>

                            </div>


                            <div class="ak-address-row">

                                <i class="bi bi-envelope"></i>

                                <div>
                                    <span>Email</span>
                                    <strong>
                                        {{ $order->email }}
                                    </strong>
                                </div>

                            </div>


                            <div class="ak-address-row">

                                <i class="bi bi-geo-alt"></i>

                                <div>
                                    <span>Adresse</span>

                                    <strong>
                                        {{ $order->address }}
                                    </strong>

                                    <small>
                                        {{ $order->neighborhood }},
                                        {{ $order->city }}
                                    </small>

                                </div>

                            </div>


                            @if($order->landmark)

                                <div class="ak-address-row">

                                    <i class="bi bi-signpost-2"></i>

                                    <div>
                                        <span>Point de repère</span>

                                        <strong>
                                            {{ $order->landmark }}
                                        </strong>
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </section>


                {{-- =============================================
                     NOTE CLIENT
                ============================================== --}}
                @if($order->customer_note)

                    <section class="ak-order-card">

                        <div class="ak-order-card-header">

                            <div>
                                <span class="ak-section-label">
                                    NOTE
                                </span>

                                <h2>
                                    Votre message
                                </h2>
                            </div>

                            <i class="bi bi-chat-left-text"></i>

                        </div>


                        <div class="ak-customer-note">

                            <i class="bi bi-quote"></i>

                            <p>
                                {{ $order->customer_note }}
                            </p>

                        </div>

                    </section>

                @endif

            </div>


            {{-- =================================================
                 SIDEBAR
            ================================================== --}}
            <aside class="ak-order-sidebar">


                {{-- =============================================
                     RÉSUMÉ
                ============================================== --}}
                <section class="ak-order-card ak-summary-card">

                    <div class="ak-order-card-header">

                        <div>
                            <span class="ak-section-label">
                                RÉSUMÉ
                            </span>

                            <h2>
                                Total de la commande
                            </h2>
                        </div>

                    </div>


                    <div class="ak-summary-lines">

                        <div>
                            <span>Sous-total</span>

                            <strong>
                                {{ number_format($order->subtotal, 0, ',', ' ') }}
                                FCFA
                            </strong>
                        </div>


                        <div>
                            <span>Livraison</span>

                            <strong>

                                @if((float) $order->delivery_fee > 0)

                                    {{ number_format($order->delivery_fee, 0, ',', ' ') }}
                                    FCFA

                                @else

                                    Gratuit

                                @endif

                            </strong>
                        </div>

                    </div>


                    <div class="ak-summary-total">

                        <span>Total</span>

                        <strong>
                            {{ number_format($order->total, 0, ',', ' ') }}
                            FCFA
                        </strong>

                    </div>

                </section>


                {{-- =============================================
                     PAIEMENT
                ============================================== --}}
                <section class="ak-order-card">

                    <div class="ak-order-card-header">

                        <div>
                            <span class="ak-section-label">
                                PAIEMENT
                            </span>

                            <h2>
                                Informations
                            </h2>
                        </div>

                        <i class="bi bi-credit-card"></i>

                    </div>


                    <div class="ak-payment-box">

                        <div class="ak-payment-row">

                            <span>Méthode</span>

                            <strong>
                                {{ $this->paymentMethodLabel() }}
                            </strong>

                        </div>


                        <div class="ak-payment-row">

                            <span>Statut</span>

                            <span class="ak-payment-status
                                ak-payment-{{ $order->payment_status }}">

                                @if($order->payment_status === 'paid')
                                    <i class="bi bi-check-circle"></i>
                                @elseif($order->payment_status === 'failed')
                                    <i class="bi bi-x-circle"></i>
                                @else
                                    <i class="bi bi-clock"></i>
                                @endif

                                {{ $this->paymentStatusLabel() }}

                            </span>

                        </div>

                    </div>

                </section>


                {{-- =============================================
                     LIVRAISON
                ============================================== --}}
                <section class="ak-order-card ak-help-card">

                    <div class="ak-help-icon">
                        <i class="bi bi-headset"></i>
                    </div>

                    <h3>
                        Besoin d'aide ?
                    </h3>

                    <p>
                        Une question concernant votre commande ?
                        Notre équipe Akatsuki est à votre disposition.
                    </p>

                    <a
                        href="{{ route('services') }}"
                        class="ak-help-button"
                    >
                        Contacter Akatsuki
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </section>

            </aside>

        </div>


        {{-- RETOUR --}}
        <div class="ak-order-bottom-actions">

            <a
                href="{{ route('mes-commandes') }}"
                class="ak-button-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Mes commandes
            </a>

            <a
                href="{{ route('catalogue') }}"
                class="ak-button-primary"
            >
                <i class="bi bi-shop"></i>
                Continuer mes achats
            </a>

        </div>

    </main>

</div>