<?php

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.base')]
#[Title('Confirmation de commande - AKATSUKI')]
class extends Component
{
    public Order $order;

    public function mount(string $orderNumber): void
    {
        $this->order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();
    }
};
?>

<div class="order-confirmation-page">
    @section('title', 'Akatsuki - Confirmation Commande')

    {{-- ================================================================ --}}
    {{-- EN-TÊTE --}}
    {{-- ================================================================ --}}

    <div class="confirmation-header">

        <div class="confirmation-success-icon">
            <i class="bi bi-check-lg"></i>
        </div>

        <span class="confirmation-eyebrow">
            AKATSUKI
        </span>

        <h1>
            Commande confirmée
        </h1>

        <p>
            Merci pour votre commande.
            Votre commande a bien été enregistrée.
        </p>

    </div>


    {{-- ================================================================ --}}
    {{-- NUMÉRO DE COMMANDE --}}
    {{-- ================================================================ --}}

    <div class="confirmation-order-number">

        <span>
            Numéro de commande
        </span>

        <strong>
            {{ $order->order_number }}
        </strong>

    </div>


    <div class="row g-4">


        {{-- ============================================================ --}}
        {{-- COLONNE GAUCHE --}}
        {{-- ============================================================ --}}

        <div class="col-lg-7">


            {{-- ======================================================== --}}
            {{-- ARTICLES --}}
            {{-- ======================================================== --}}

            <div class="confirmation-card">

                <div class="confirmation-card-header">

                    <div class="confirmation-card-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>

                    <div>

                        <h2>
                            Votre commande
                        </h2>

                        <p>
                            Détail des articles commandés.
                        </p>

                    </div>

                </div>


                <div class="confirmation-card-body">

                    @foreach ($order->items as $item)

                        <div class="confirmation-item">

                            <div class="confirmation-item-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>


                            <div class="confirmation-item-info">

                                <strong>
                                    {{ $item->product_name }}
                                </strong>

                                <span>
                                    Quantité :
                                    {{ $item->quantity }}
                                </span>

                            </div>


                            <div class="confirmation-item-price">

                                {{ number_format(
                                    (float) $item->subtotal,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                FCFA

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- ======================================================== --}}
            {{-- INFORMATIONS CLIENT --}}
            {{-- ======================================================== --}}

            <div class="confirmation-card">

                <div class="confirmation-card-header">

                    <div class="confirmation-card-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    <div>

                        <h2>
                            Informations client
                        </h2>

                        <p>
                            Informations utilisées pour votre commande.
                        </p>

                    </div>

                </div>


                <div class="confirmation-card-body">

                    <div class="confirmation-info-grid">


                        {{-- NOM --}}
                        <div class="confirmation-info">

                            <span>
                                Nom complet
                            </span>

                            <strong>
                                {{ $order->customer_name }}
                            </strong>

                        </div>


                        {{-- EMAIL --}}
                        <div class="confirmation-info">

                            <span>
                                Email
                            </span>

                            <strong>
                                {{ $order->email }}
                            </strong>

                        </div>


                        {{-- TELEPHONE --}}
                        <div class="confirmation-info">

                            <span>
                                Téléphone
                            </span>

                            <strong>
                                {{ $order->phone }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ======================================================== --}}
            {{-- LIVRAISON --}}
            {{-- ======================================================== --}}

            <div class="confirmation-card">

                <div class="confirmation-card-header">

                    <div class="confirmation-card-icon">
                        <i class="bi bi-truck"></i>
                    </div>

                    <div>

                        <h2>
                            Livraison
                        </h2>

                        <p>
                            Mode de réception de votre commande.
                        </p>

                    </div>

                </div>


                <div class="confirmation-card-body">


                    @if ($order->delivery_method === 'home_delivery')

                        <div class="confirmation-delivery">

                            <div class="confirmation-delivery-icon">

                                <i class="bi bi-truck"></i>

                            </div>


                            <div>

                                <strong>
                                    Livraison à domicile
                                </strong>

                                <p>
                                    {{ $order->city }},
                                    {{ $order->neighborhood }}
                                </p>

                                <p>
                                    {{ $order->address }}
                                </p>


                                @if ($order->landmark)

                                    <p>
                                        <strong>
                                            Point de repère :
                                        </strong>

                                        {{ $order->landmark }}
                                    </p>

                                @endif

                            </div>

                        </div>

                    @else

                        <div class="confirmation-delivery">

                            <div class="confirmation-delivery-icon">

                                <i class="bi bi-shop"></i>

                            </div>


                            <div>

                                <strong>
                                    Retrait en boutique
                                </strong>

                                <p>
                                    Votre commande sera préparée pour
                                    un retrait en boutique.
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- ======================================================== --}}
            {{-- PAIEMENT --}}
            {{-- ======================================================== --}}

            <div class="confirmation-card">

                <div class="confirmation-card-header">

                    <div class="confirmation-card-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>

                    <div>

                        <h2>
                            Paiement
                        </h2>

                        <p>
                            Informations concernant votre paiement.
                        </p>

                    </div>

                </div>


                <div class="confirmation-card-body">


                    <div class="confirmation-info-grid">


                        <div class="confirmation-info">

                            <span>
                                Moyen de paiement
                            </span>

                            <strong>

                                @switch($order->payment_method)

                                    @case('cash_on_delivery')
                                        Paiement à la livraison
                                        @break

                                    @case('mtn_momo')
                                        MTN Mobile Money
                                        @break

                                    @case('moov_money')
                                        Moov Money
                                        @break

                                    @default
                                        {{ $order->payment_method }}

                                @endswitch

                            </strong>

                        </div>


                        <div class="confirmation-info">

                            <span>
                                Statut du paiement
                            </span>

                            <strong>

                                @switch($order->payment_status)

                                    @case('paid')
                                        <span class="confirmation-status paid">
                                            Payé
                                        </span>
                                        @break

                                    @case('failed')
                                        <span class="confirmation-status failed">
                                            Échec
                                        </span>
                                        @break

                                    @default
                                        <span class="confirmation-status pending">
                                            En attente
                                        </span>

                                @endswitch

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================ --}}
        {{-- COLONNE DROITE --}}
        {{-- ============================================================ --}}

        <div class="col-lg-5">


            {{-- ======================================================== --}}
            {{-- RÉSUMÉ --}}
            {{-- ======================================================== --}}

            <div class="confirmation-summary">

                <div class="confirmation-summary-header">

                    <span>
                        RÉCAPITULATIF
                    </span>

                    <h2>
                        Total de la commande
                    </h2>

                </div>


                <div class="confirmation-summary-body">


                    {{-- SOUS-TOTAL --}}
                    <div class="confirmation-summary-row">

                        <span>
                            Sous-total
                        </span>

                        <strong>

                            {{ number_format(
                                (float) $order->subtotal,
                                0,
                                ',',
                                ' '
                            ) }}

                            FCFA

                        </strong>

                    </div>


                    {{-- LIVRAISON --}}
                    <div class="confirmation-summary-row">

                        <span>
                            Livraison
                        </span>

                        <strong>

                            @if ((float) $order->delivery_fee > 0)

                                {{ number_format(
                                    (float) $order->delivery_fee,
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


                    <div class="confirmation-summary-divider"></div>


                    {{-- TOTAL --}}
                    <div class="confirmation-summary-total">

                        <span>
                            Total
                        </span>

                        <strong>

                            {{ number_format(
                                (float) $order->total,
                                0,
                                ',',
                                ' '
                            ) }}

                            FCFA

                        </strong>

                    </div>

                </div>


                {{-- ==================================================== --}}
                {{-- STATUT --}}
                {{-- ==================================================== --}}

                <div class="confirmation-status-box">

                    <div class="confirmation-status-icon">

                        <i class="bi bi-clock-history"></i>

                    </div>


                    <div>

                        <strong>
                            Commande en attente
                        </strong>

                        <p>
                            Notre équipe va traiter votre commande
                            dans les meilleurs délais.
                        </p>

                    </div>

                </div>


                {{-- ==================================================== --}}
                {{-- ACTIONS --}}
                {{-- ==================================================== --}}

                <div class="confirmation-actions">

                    <a
                        href="{{ route('catalogue') }}"
                        class="btn btn-dark w-100"
                    >
                        <i class="bi bi-shop me-2"></i>
                        Continuer mes achats
                    </a>


                    <a
                        href="{{ route('panier') }}"
                        class="btn btn-outline-dark w-100"
                    >
                        <i class="bi bi-cart me-2"></i>
                        Retour au panier
                    </a>

                </div>


                {{-- ==================================================== --}}
                {{-- SÉCURITÉ --}}
                {{-- ==================================================== --}}

                <div class="confirmation-secure">

                    <i class="bi bi-shield-check"></i>

                    <span>
                        Merci de faire confiance à AKATSUKI.
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>