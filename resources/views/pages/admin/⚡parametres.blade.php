<?php

use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.admin')]
#[Title('Paramètres')]
class extends Component
{
    /*
    |--------------------------------------------------------------------------
    | INFORMATIONS DE LA BOUTIQUE
    |--------------------------------------------------------------------------
    */

    public string $shopName = 'AKATSUKI';

    public string $shopEmail = '';

    public string $shopPhone = '';

    public string $shopAddress = '';

    public string $shopDescription = '';

    /*
    |--------------------------------------------------------------------------
    | LIVRAISON
    |--------------------------------------------------------------------------
    */

    public float $deliveryFee = 0;

    public float $freeDeliveryMinimum = 0;

    public bool $deliveryEnabled = true;

    /*
    |--------------------------------------------------------------------------
    | PAIEMENT
    |--------------------------------------------------------------------------
    */

    public bool $cashPayment = true;

    public bool $mobileMoneyPayment = true;

    public bool $bankPayment = false;

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    public bool $orderNotifications = true;

    public bool $stockNotifications = true;

    public bool $customerNotifications = true;

    /*
    |--------------------------------------------------------------------------
    | APPARENCE
    |--------------------------------------------------------------------------
    */

    public string $currency = 'FCFA';

    public string $theme = 'light';

    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $settings = Cache::get('akatsuki_settings', []);

        $this->shopName = $settings['shopName'] ?? 'AKATSUKI';
        $this->shopEmail = $settings['shopEmail'] ?? '';
        $this->shopPhone = $settings['shopPhone'] ?? '';
        $this->shopAddress = $settings['shopAddress'] ?? '';
        $this->shopDescription = $settings['shopDescription'] ?? '';

        $this->deliveryFee = (float) ($settings['deliveryFee'] ?? 0);
        $this->freeDeliveryMinimum = (float) ($settings['freeDeliveryMinimum'] ?? 0);
        $this->deliveryEnabled = (bool) ($settings['deliveryEnabled'] ?? true);

        $this->cashPayment = (bool) ($settings['cashPayment'] ?? true);
        $this->mobileMoneyPayment = (bool) ($settings['mobileMoneyPayment'] ?? true);
        $this->bankPayment = (bool) ($settings['bankPayment'] ?? false);

        $this->orderNotifications = (bool) ($settings['orderNotifications'] ?? true);
        $this->stockNotifications = (bool) ($settings['stockNotifications'] ?? true);
        $this->customerNotifications = (bool) ($settings['customerNotifications'] ?? true);

        $this->currency = $settings['currency'] ?? 'FCFA';
        $this->theme = $settings['theme'] ?? 'light';
    }

    /*
    |--------------------------------------------------------------------------
    | SAUVEGARDE
    |--------------------------------------------------------------------------
    */

    public function saveSettings(): void
    {
        $this->validate([
            'shopName' => ['required', 'string', 'max:100'],
            'shopEmail' => ['nullable', 'email', 'max:150'],
            'shopPhone' => ['nullable', 'string', 'max:30'],
            'shopAddress' => ['nullable', 'string', 'max:255'],
            'shopDescription' => ['nullable', 'string', 'max:500'],

            'deliveryFee' => ['required', 'numeric', 'min:0'],
            'freeDeliveryMinimum' => ['required', 'numeric', 'min:0'],

            'currency' => ['required', 'string', 'max:10'],
            'theme' => ['required', 'in:light,dark'],
        ], [
            'shopName.required' => 'Le nom de la boutique est obligatoire.',
            'shopEmail.email' => 'Veuillez saisir une adresse email valide.',
            'deliveryFee.numeric' => 'Les frais de livraison doivent être un nombre.',
            'deliveryFee.min' => 'Les frais de livraison ne peuvent pas être négatifs.',
            'freeDeliveryMinimum.numeric' => 'Le montant minimum doit être un nombre.',
            'freeDeliveryMinimum.min' => 'Le montant minimum ne peut pas être négatif.',
        ]);

        Cache::forever('akatsuki_settings', [
            'shopName' => $this->shopName,
            'shopEmail' => $this->shopEmail,
            'shopPhone' => $this->shopPhone,
            'shopAddress' => $this->shopAddress,
            'shopDescription' => $this->shopDescription,

            'deliveryFee' => $this->deliveryFee,
            'freeDeliveryMinimum' => $this->freeDeliveryMinimum,
            'deliveryEnabled' => $this->deliveryEnabled,

            'cashPayment' => $this->cashPayment,
            'mobileMoneyPayment' => $this->mobileMoneyPayment,
            'bankPayment' => $this->bankPayment,

            'orderNotifications' => $this->orderNotifications,
            'stockNotifications' => $this->stockNotifications,
            'customerNotifications' => $this->customerNotifications,

            'currency' => $this->currency,
            'theme' => $this->theme,
        ]);

        session()->flash('settings-success', 'Les paramètres ont été enregistrés avec succès.');
    }

    /*
    |--------------------------------------------------------------------------
    | RÉINITIALISATION
    |--------------------------------------------------------------------------
    */

    public function resetSettings(): void
    {
        Cache::forget('akatsuki_settings');

        $this->shopName = 'AKATSUKI';
        $this->shopEmail = '';
        $this->shopPhone = '';
        $this->shopAddress = '';
        $this->shopDescription = '';

        $this->deliveryFee = 0;
        $this->freeDeliveryMinimum = 0;
        $this->deliveryEnabled = true;

        $this->cashPayment = true;
        $this->mobileMoneyPayment = true;
        $this->bankPayment = false;

        $this->orderNotifications = true;
        $this->stockNotifications = true;
        $this->customerNotifications = true;

        $this->currency = 'FCFA';
        $this->theme = 'light';

        session()->flash('settings-success', 'Les paramètres ont été réinitialisés.');
    }
};
?>

<div class="admin-settings">
    @section('title', 'Admin -Paramètres ')
    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="settings-page-header">

        <div>
            <span class="settings-eyebrow">
                CONFIGURATION
            </span>

            <h1 class="settings-page-title">
                Paramètres
            </h1>

            <p class="settings-page-subtitle">
                Gérez les informations et le fonctionnement de votre boutique AKATSUKI.
            </p>
        </div>

        <div class="settings-header-icon">
            <i class="bi bi-gear"></i>
        </div>

    </div>


    {{-- =========================================================
         MESSAGE DE SUCCÈS
    ========================================================== --}}

    @if (session()->has('settings-success'))
        <div class="settings-alert settings-alert-success">
            <div class="settings-alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>
                <strong>Modification enregistrée</strong>
                <span>{{ session('settings-success') }}</span>
            </div>
        </div>
    @endif


    {{-- =========================================================
         ERREURS
    ========================================================== --}}

    @if ($errors->any())
        <div class="settings-alert settings-alert-danger">
            <div class="settings-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>
                <strong>Impossible d'enregistrer</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- =========================================================
         CONTENU
    ========================================================== --}}

    <div class="settings-layout">

        {{-- =====================================================
             COLONNE PRINCIPALE
        ====================================================== --}}

        <div class="settings-main">

            {{-- =================================================
                 INFORMATIONS DE LA BOUTIQUE
            ================================================== --}}

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-section-icon shop">
                        <i class="bi bi-shop"></i>
                    </div>

                    <div>
                        <h2 class="settings-card-title">
                            Informations de la boutique
                        </h2>

                        <p class="settings-card-description">
                            Les informations générales de votre boutique.
                        </p>
                    </div>

                </div>

                <div class="settings-card-body">

                    <div class="settings-form-grid">

                        <div class="settings-field">
                            <label for="shopName">
                                Nom de la boutique
                            </label>

                            <input
                                type="text"
                                id="shopName"
                                wire:model="shopName"
                                class="settings-input @error('shopName') is-invalid @enderror"
                                placeholder="AKATSUKI"
                            >

                            @error('shopName')
                                <span class="settings-error">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="settings-field">
                            <label for="shopEmail">
                                Email
                            </label>

                            <input
                                type="email"
                                id="shopEmail"
                                wire:model="shopEmail"
                                class="settings-input @error('shopEmail') is-invalid @enderror"
                                placeholder="contact@akatsuki.com"
                            >

                            @error('shopEmail')
                                <span class="settings-error">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="settings-field">
                            <label for="shopPhone">
                                Numéro de téléphone
                            </label>

                            <input
                                type="text"
                                id="shopPhone"
                                wire:model="shopPhone"
                                class="settings-input"
                                placeholder="+229 XX XX XX XX"
                            >
                        </div>


                        <div class="settings-field">
                            <label for="shopAddress">
                                Adresse
                            </label>

                            <input
                                type="text"
                                id="shopAddress"
                                wire:model="shopAddress"
                                class="settings-input"
                                placeholder="Cotonou, Bénin"
                            >
                        </div>

                    </div>


                    <div class="settings-field settings-field-full">

                        <label for="shopDescription">
                            Description de la boutique
                        </label>

                        <textarea
                            id="shopDescription"
                            wire:model="shopDescription"
                            class="settings-textarea"
                            rows="4"
                            placeholder="Présentez brièvement votre boutique..."
                        ></textarea>

                        <div class="settings-field-help">
                            Cette description peut être utilisée sur votre site public.
                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 LIVRAISON
            ================================================== --}}

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-section-icon delivery">
                        <i class="bi bi-truck"></i>
                    </div>

                    <div>
                        <h2 class="settings-card-title">
                            Livraison
                        </h2>

                        <p class="settings-card-description">
                            Configurez les frais et les conditions de livraison.
                        </p>
                    </div>

                </div>

                <div class="settings-card-body">

                    <div class="settings-option-row">

                        <div class="settings-option-content">

                            <div class="settings-option-title">
                                Activer la livraison
                            </div>

                            <div class="settings-option-description">
                                Permettre aux clients de choisir la livraison lors de leur commande.
                            </div>

                        </div>

                        <label class="settings-switch">
                            <input
                                type="checkbox"
                                wire:model="deliveryEnabled"
                            >

                            <span class="settings-switch-slider"></span>
                        </label>

                    </div>


                    <div class="settings-divider"></div>


                    <div class="settings-form-grid">

                        <div class="settings-field">

                            <label for="deliveryFee">
                                Frais de livraison
                            </label>

                            <div class="settings-input-group">

                                <input
                                    type="number"
                                    id="deliveryFee"
                                    wire:model="deliveryFee"
                                    min="0"
                                    step="1"
                                    class="settings-input"
                                >

                                <span class="settings-input-addon">
                                    FCFA
                                </span>

                            </div>

                            <div class="settings-field-help">
                                Montant facturé au client pour une livraison standard.
                            </div>

                        </div>


                        <div class="settings-field">

                            <label for="freeDeliveryMinimum">
                                Livraison gratuite à partir de
                            </label>

                            <div class="settings-input-group">

                                <input
                                    type="number"
                                    id="freeDeliveryMinimum"
                                    wire:model="freeDeliveryMinimum"
                                    min="0"
                                    step="1"
                                    class="settings-input"
                                >

                                <span class="settings-input-addon">
                                    FCFA
                                </span>

                            </div>

                            <div class="settings-field-help">
                                Mettez 0 pour désactiver la livraison gratuite.
                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 PAIEMENT
            ================================================== --}}

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-section-icon payment">
                        <i class="bi bi-credit-card"></i>
                    </div>

                    <div>
                        <h2 class="settings-card-title">
                            Moyens de paiement
                        </h2>

                        <p class="settings-card-description">
                            Choisissez les moyens de paiement disponibles pour vos clients.
                        </p>
                    </div>

                </div>

                <div class="settings-card-body settings-options-list">

                    <div class="settings-option-row">

                        <div class="settings-option-left">

                            <div class="settings-option-icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>

                            <div class="settings-option-content">

                                <div class="settings-option-title">
                                    Paiement à la livraison
                                </div>

                                <div class="settings-option-description">
                                    Le client paie lors de la réception de sa commande.
                                </div>

                            </div>

                        </div>

                        <label class="settings-switch">
                            <input
                                type="checkbox"
                                wire:model="cashPayment"
                            >

                            <span class="settings-switch-slider"></span>
                        </label>

                    </div>


                    <div class="settings-option-row">

                        <div class="settings-option-left">

                            <div class="settings-option-icon">
                                <i class="bi bi-phone"></i>
                            </div>

                            <div class="settings-option-content">

                                <div class="settings-option-title">
                                    Mobile Money
                                </div>

                                <div class="settings-option-description">
                                    Autoriser les paiements via les services Mobile Money.
                                </div>

                            </div>

                        </div>

                        <label class="settings-switch">
                            <input
                                type="checkbox"
                                wire:model="mobileMoneyPayment"
                            >

                            <span class="settings-switch-slider"></span>
                        </label>

                    </div>


                    <div class="settings-option-row">

                        <div class="settings-option-left">

                            <div class="settings-option-icon">
                                <i class="bi bi-bank"></i>
                            </div>

                            <div class="settings-option-content">

                                <div class="settings-option-title">
                                    Virement bancaire
                                </div>

                                <div class="settings-option-description">
                                    Autoriser les paiements par virement bancaire.
                                </div>

                            </div>

                        </div>

                        <label class="settings-switch">
                            <input
                                type="checkbox"
                                wire:model="bankPayment"
                            >

                            <span class="settings-switch-slider"></span>
                        </label>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 NOTIFICATIONS
            ================================================== --}}

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-section-icon notifications">
                        <i class="bi bi-bell"></i>
                    </div>

                    <div>
                        <h2 class="settings-card-title">
                            Notifications
                        </h2>

                        <p class="settings-card-description">
                            Choisissez les notifications que vous souhaitez recevoir.
                        </p>
                    </div>

                </div>

                <div class="settings-card-body settings-options-list">

                    <div class="settings-option-row">

                        <div class="settings-option-left">

                            <div class="settings-option-icon">
                                <i class="bi bi-bag-check"></i>
                            </div>

                            <div class="settings-option-content">

                                <div class="settings-option-title">
                                    Nouvelles commandes
                                </div>

                                <div class="settings-option-description">
                                    Recevoir une notification lorsqu'une nouvelle commande est créée.
                                </div>

                            </div>

                        </div>

                        <label class="settings-switch">
                            <input
                                type="checkbox"
                                wire:model="orderNotifications"
                            >

                            <span class="settings-switch-slider"></span>
                        </label>

                    </div>


                    <div class="settings-option-row">

                        <div class="settings-option-left">

                            <div class="settings-option-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <div class="settings-option-content">

                                <div class="settings-option-title">
                                    Stock faible
                                </div>

                                <div class="settings-option-description">
                                    Être averti lorsqu'un produit arrive à un niveau de stock faible.
                                </div>

                            </div>

                        </div>

                        <label class="settings-switch">
                            <input
                                type="checkbox"
                                wire:model="stockNotifications"
                            >

                            <span class="settings-switch-slider"></span>
                        </label>

                    </div>


                    <div class="settings-option-row">

                        <div class="settings-option-left">

                            <div class="settings-option-icon">
                                <i class="bi bi-people"></i>
                            </div>

                            <div class="settings-option-content">

                                <div class="settings-option-title">
                                    Clients
                                </div>

                                <div class="settings-option-description">
                                    Recevoir des notifications concernant les nouveaux clients.
                                </div>

                            </div>

                        </div>

                        <label class="settings-switch">
                            <input
                                type="checkbox"
                                wire:model="customerNotifications"
                            >

                            <span class="settings-switch-slider"></span>
                        </label>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 APPARENCE
            ================================================== --}}

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-section-icon appearance">
                        <i class="bi bi-palette"></i>
                    </div>

                    <div>
                        <h2 class="settings-card-title">
                            Apparence
                        </h2>

                        <p class="settings-card-description">
                            Configurez l'apparence de votre espace d'administration.
                        </p>
                    </div>

                </div>

                <div class="settings-card-body">

                    <div class="settings-form-grid">

                        <div class="settings-field">

                            <label for="currency">
                                Devise
                            </label>

                            <select
                                id="currency"
                                wire:model="currency"
                                class="settings-input settings-select"
                            >
                                <option value="FCFA">
                                    FCFA
                                </option>

                                <option value="EUR">
                                    EUR
                                </option>

                                <option value="USD">
                                    USD
                                </option>
                            </select>

                        </div>


                        <div class="settings-field">

                            <label for="theme">
                                Thème administrateur
                            </label>

                            <select
                                id="theme"
                                wire:model="theme"
                                class="settings-input settings-select"
                            >
                                <option value="light">
                                    Clair
                                </option>

                                <option value="dark">
                                    Sombre
                                </option>
                            </select>

                        </div>

                    </div>

                </div>

            </section>

        </div>


        {{-- =====================================================
             COLONNE LATÉRALE
        ====================================================== --}}

        <aside class="settings-sidebar">

            {{-- Résumé --}}

            <div class="settings-summary-card">

                <div class="settings-summary-logo">
                    AKA<span>TSUKI</span>
                </div>

                <p class="settings-summary-text">
                    Votre boutique en ligne, votre univers.
                </p>

                <div class="settings-summary-divider"></div>

                <div class="settings-summary-item">

                    <span>
                        <i class="bi bi-shop"></i>
                        Boutique
                    </span>

                    <strong>
                        {{ $shopName ?: 'AKATSUKI' }}
                    </strong>

                </div>


                <div class="settings-summary-item">

                    <span>
                        <i class="bi bi-currency-exchange"></i>
                        Devise
                    </span>

                    <strong>
                        {{ $currency }}
                    </strong>

                </div>


                <div class="settings-summary-item">

                    <span>
                        <i class="bi bi-truck"></i>
                        Livraison
                    </span>

                    <strong class="{{ $deliveryEnabled ? 'text-success' : 'text-muted' }}">
                        {{ $deliveryEnabled ? 'Activée' : 'Désactivée' }}
                    </strong>

                </div>

            </div>


            {{-- Aide --}}

            <div class="settings-help-card">

                <div class="settings-help-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <h3>
                    Besoin d'aide ?
                </h3>

                <p>
                    Vérifiez vos paramètres avant de mettre votre boutique en production.
                </p>

                <a href="{{ route('admin.dashboard') }}" class="settings-help-link">
                    Retour au dashboard
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            {{-- Réinitialisation --}}

            <div class="settings-danger-card">

                <div class="settings-danger-icon">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </div>

                <div>

                    <h3>
                        Réinitialiser
                    </h3>

                    <p>
                        Restaurer les paramètres par défaut.
                    </p>

                </div>

                <button
                    type="button"
                    wire:click="resetSettings"
                    wire:confirm="Voulez-vous vraiment réinitialiser tous les paramètres ?"
                    class="settings-reset-btn"
                >
                    Réinitialiser
                </button>

            </div>

        </aside>

    </div>


    {{-- =========================================================
         BARRE DE SAUVEGARDE
    ========================================================== --}}

    <div class="settings-save-bar">

        <div class="settings-save-info">

            <div class="settings-save-icon">
                <i class="bi bi-cloud-check"></i>
            </div>

            <div>
                <strong>
                    Paramètres de la boutique
                </strong>

                <span>
                    Enregistrez vos modifications pour les appliquer.
                </span>
            </div>

        </div>


        <div class="settings-save-actions">

            <button
                type="button"
                wire:click="resetSettings"
                class="settings-cancel-btn"
            >
                Réinitialiser
            </button>

            <button
                type="button"
                wire:click="saveSettings"
                wire:loading.attr="disabled"
                class="settings-save-btn"
            >

                <span wire:loading.remove wire:target="saveSettings">
                    <i class="bi bi-check-lg"></i>
                    Enregistrer
                </span>

                <span wire:loading wire:target="saveSettings">
                    <span class="settings-spinner"></span>
                    Enregistrement...
                </span>

            </button>

        </div>

    </div>

</div>