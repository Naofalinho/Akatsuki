<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Flux\Flux;

new #[Title('Mon profil')] class extends Component
{
    use ProfileValidationRules;

    public string $name = '';

    public string $email = '';

    public string $sexe = '';

    public string $phone = '';


    /**
     * Initialisation du profil.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->sexe = $user->sexe ?? '';
        $this->phone = $user->phone ?? '';
    }


    /**
     * Mise à jour du profil.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'sexe' => [
                'nullable',
                'in:homme,femme',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ]);


        $user->fill($validated);


        /*
        |--------------------------------------------------------------------------
        | Si l'email change, il doit être vérifié à nouveau.
        |--------------------------------------------------------------------------
        */

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }


        $user->save();


        Flux::toast(
            variant: 'success',
            text: 'Profil mis à jour avec succès.'
        );
    }


    /**
     * Renvoyer l'email de vérification.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {

            $this->redirectIntended(
                default: route('dashboard', absolute: false)
            );

            return;
        }


        $user->sendEmailVerificationNotification();


        Session::flash(
            'status',
            'verification-link-sent'
        );
    }


    /**
     * Vérifie si l'email est non vérifié.
     */
    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail
            && ! Auth::user()->hasVerifiedEmail();
    }


    /**
     * Affichage du profil.
     */
    public function render()
    {
        return view('pages.settings.⚡profile');
    }
};
?>


<div class="ak-profile-page">


    {{-- =========================================================
    HEADER
    ========================================================== --}}

    <section class="profile-header">

        <div class="container">

            <div class="profile-header-content">

                <div>

                    <span class="profile-subtitle">
                        MON COMPTE
                    </span>

                    <h1>
                        Mon profil
                    </h1>

                    <p>
                        Gérez vos informations personnelles et votre compte AKATSUKI.
                    </p>

                </div>


                {{-- AVATAR --}}
                <div class="profile-avatar">

                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    CONTENU
    ========================================================== --}}

    <div class="container py-5">

        <div class="row g-4">


            {{-- =====================================================
            SIDEBAR
            ====================================================== --}}

            <div class="col-lg-3">

                <div class="profile-sidebar">


                    {{-- UTILISATEUR --}}
                    <div class="profile-user">

                        <div class="profile-mini-avatar">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </div>


                        <div>

                            <h5>
                                {{ auth()->user()->name }}
                            </h5>

                            <small>
                                {{ auth()->user()->email }}
                            </small>

                        </div>

                    </div>



                    {{-- MENU --}}
                    <nav class="profile-menu">

                        <a href="#informations" class="active">

                            <i class="bi bi-person"></i>

                            <span>
                                Informations personnelles
                            </span>

                        </a>


                        <a href="{{ route('mes-commandes') }}">
                            <i class="bi bi-bag"></i>
                            <span>Mes commandes</span>
                        </a>


                        <a href="{{ route('favoris') }}">

                            <i class="bi bi-heart"></i>

                            <span>
                                Mes favoris
                            </span>

                        </a>


                        <a href="{{ route('security.edit') }}">

                            <i class="bi bi-shield-lock"></i>

                            <span>
                                Sécurité
                            </span>

                        </a>


                        {{-- DECONNEXION --}}
                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit" class="profile-logout">

                                <i class="bi bi-box-arrow-right"></i>

                                <span>
                                    Déconnexion
                                </span>

                            </button>

                        </form>

                    </nav>

                </div>

            </div>



            {{-- =====================================================
            CONTENU PRINCIPAL
            ====================================================== --}}

            <div class="col-lg-9">


                {{-- =================================================
                INFORMATIONS PERSONNELLES
                ================================================== --}}

                <section id="informations" class="profile-card">

                    <div class="profile-card-header">

                        <div>

                            <span class="section-label">
                                PROFIL
                            </span>

                            <h2>
                                Informations personnelles
                            </h2>

                            <p>
                                Modifiez les informations associées à votre compte.
                            </p>

                        </div>


                        <div class="section-icon">

                            <i class="bi bi-person"></i>

                        </div>

                    </div>



                    {{-- FORMULAIRE LIVEWIRE --}}
                    <form wire:submit="updateProfileInformation">

                        <div class="row g-4">


                            {{-- NOM --}}
                            <div class="col-md-6">

                                <label for="name">
                                    Nom complet
                                </label>

                                <div class="input-wrapper">

                                    <i class="bi bi-person"></i>

                                    <input type="text" id="name" wire:model="name" placeholder="Votre nom complet"
                                        autocomplete="name" required>

                                </div>


                                @error('name')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                                @enderror

                            </div>



                            {{-- EMAIL --}}
                            <div class="col-md-6">

                                <label for="email">
                                    Adresse email
                                </label>

                                <div class="input-wrapper">

                                    <i class="bi bi-envelope"></i>

                                    <input type="email" id="email" wire:model="email" placeholder="exemple@email.com"
                                        autocomplete="email" required>

                                </div>


                                @error('email')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                                @enderror

                            </div>



                            {{-- SEXE --}}
                            <div class="col-md-6">

                                <label for="sexe">
                                    Sexe
                                </label>

                                <div class="input-wrapper">

                                    <i class="bi bi-person-vcard"></i>

                                    <select id="sexe" wire:model="sexe">

                                        <option value="">
                                            Sélectionner
                                        </option>

                                        <option value="homme">
                                            Homme
                                        </option>

                                        <option value="femme">
                                            Femme
                                        </option>

                                    </select>

                                </div>


                                @error('sexe')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                                @enderror

                            </div>



                            {{-- TELEPHONE --}}
                            <div class="col-md-6">

                                <label for="phone">
                                    Numéro de téléphone
                                </label>

                                <div class="input-wrapper">

                                    <i class="bi bi-telephone"></i>

                                    <input type="tel" id="phone" wire:model="phone" placeholder="+229 XX XX XX XX"
                                        autocomplete="tel">

                                </div>


                                @error('phone')

                                <small class="text-danger">
                                    {{ $message }}
                                </small>

                                @enderror

                            </div>

                        </div>



                        {{-- EMAIL NON VERIFIE --}}
                        @if ($this->hasUnverifiedEmail)

                        <div class="verification-box">

                            <div class="verification-icon">

                                <i class="bi bi-envelope-exclamation"></i>

                            </div>


                            <div class="verification-content">

                                <strong>
                                    Adresse email non vérifiée
                                </strong>

                                <p>
                                    Votre adresse email n'a pas encore été vérifiée.
                                </p>


                                <button type="button" wire:click="resendVerificationNotification"
                                    class="verification-link">

                                    Renvoyer l'email de vérification

                                </button>


                                @if (session('status') === 'verification-link-sent')

                                <div class="verification-success">

                                    <i class="bi bi-check-circle"></i>

                                    Un nouveau lien de vérification
                                    a été envoyé à votre adresse email.

                                </div>

                                @endif

                            </div>

                        </div>

                        @endif



                        {{-- BOUTON --}}
                        <div class="profile-actions">

                            <button type="submit" class="btn-ak-primary" wire:loading.attr="disabled">

                                <span wire:loading.remove wire:target="updateProfileInformation">

                                    <i class="bi bi-check-lg"></i>

                                    Enregistrer les modifications

                                </span>


                                <span wire:loading wire:target="updateProfileInformation">

                                    <i class="bi bi-arrow-repeat"></i>

                                    Enregistrement...

                                </span>

                            </button>

                        </div>

                    </form>

                </section>



                {{-- =================================================
                COMMANDES
                ================================================== --}}

                <section id="commandes" class="profile-card mt-4">

                    <div class="profile-card-header">

                        <div>

                            <span class="section-label">
                                ACHATS
                            </span>

                            <h2>
                                Mes commandes
                            </h2>

                            <p>
                                Consultez l'historique de vos commandes.
                            </p>

                        </div>


                        <div class="section-icon">

                            <i class="bi bi-bag"></i>

                        </div>

                    </div>


                    <div class="profile-empty">

                        <div class="empty-icon">

                            <i class="bi bi-bag-x"></i>

                        </div>


                        <h4>
                            Aucune commande récente
                        </h4>


                        <p>
                            Vos commandes apparaîtront ici après votre premier achat.
                        </p>


                        <a href="{{ route('catalogue') }}" class="btn-ak-primary">

                            <i class="bi bi-shop"></i>

                            Découvrir le catalogue

                        </a>

                    </div>

                </section>



                {{-- =================================================
                FAVORIS
                ================================================== --}}

                <section id="favoris" class="profile-card mt-4">

                    <div class="profile-card-header">

                        <div>

                            <span class="section-label">
                                MA SÉLECTION
                            </span>

                            <h2>
                                Mes favoris
                            </h2>

                            <p>
                                Retrouvez vos produits préférés.
                            </p>

                        </div>


                        <div class="section-icon">

                            <i class="bi bi-heart"></i>

                        </div>

                    </div>


                    <div class="profile-empty">

                        <div class="empty-icon">

                            <i class="bi bi-heart"></i>

                        </div>


                        <h4>
                            Mes produits favoris
                        </h4>


                        <p>
                            Retrouvez tous les produits que vous avez ajoutés à vos favoris.
                        </p>


                        <a href="{{ route('favoris') }}" class="btn-ak-primary">

                            <i class="bi bi-heart"></i>

                            Voir mes favoris

                        </a>

                    </div>

                </section>



                {{-- =================================================
                SECURITE
                ================================================== --}}

                <section id="securite" class="profile-card mt-4">

                    <div class="profile-card-header">

                        <div>

                            <span class="section-label">
                                SÉCURITÉ
                            </span>

                            <h2>
                                Sécurité du compte
                            </h2>

                            <p>
                                Gérez la sécurité de votre compte AKATSUKI.
                            </p>

                        </div>


                        <div class="section-icon">

                            <i class="bi bi-shield-lock"></i>

                        </div>

                    </div>


                    <div class="security-item">

                        <div class="security-icon">

                            <i class="bi bi-key"></i>

                        </div>


                        <div class="security-content">

                            <h5>
                                Mot de passe et sécurité
                            </h5>

                            <p>
                                Gérez votre mot de passe, vos passkeys
                                et les paramètres de sécurité.
                            </p>

                        </div>


                        <a href="{{ route('security.edit') }}" class="btn-security">

                            Gérer

                        </a>

                    </div>

                </section>

            </div>

        </div>

    </div>

</div>