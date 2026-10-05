<?php

use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

new #[Title('Demande d’emploi')] class extends \Livewire\Component
{
    use WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | Informations personnelles
    |--------------------------------------------------------------------------
    */

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $gender = '';
    public string $city = '';

    /*
    |--------------------------------------------------------------------------
    | Informations professionnelles
    |--------------------------------------------------------------------------
    */

    public string $position = '';
    public string $domain = '';
    public string $education_level = '';
    public int $experience_years = 0;
    public string $availability = '';

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    public $cv = null;
    public $cover_letter = null;

    /*
    |--------------------------------------------------------------------------
    | Message
    |--------------------------------------------------------------------------
    */

    public string $message = '';

    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->gender = $user->sexe ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'gender' => [
                'nullable',
                'in:homme,femme',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'position' => [
                'required',
                'string',
                'max:255',
            ],

            'domain' => [
                'required',
                'string',
                'max:255',
            ],

            'education_level' => [
                'nullable',
                'string',
                'max:255',
            ],

            'experience_years' => [
                'required',
                'integer',
                'min:0',
                'max:60',
            ],

            'availability' => [
                'required',
                'string',
                'max:255',
            ],

            'cv' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],

            'cover_letter' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],

            'message' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Envoi de la candidature
    |--------------------------------------------------------------------------
    */

    public function submit(): void
    {
        $validated = $this->validate();

        /*
        |--------------------------------------------------------------------------
        | Upload CV
        |--------------------------------------------------------------------------
        */

        $cvPath = $this->cv->store(
            'job-applications/cv',
            'public'
        );

        /*
        |--------------------------------------------------------------------------
        | Upload lettre de motivation
        |--------------------------------------------------------------------------
        */

        $coverLetterPath = null;

        if ($this->cover_letter) {
            $coverLetterPath = $this->cover_letter->store(
                'job-applications/cover-letters',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Enregistrement
        |--------------------------------------------------------------------------
        */

        JobApplication::create([
            'user_id' => Auth::id(),

            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],

            'gender' => $validated['gender'] ?? null,
            'city' => $validated['city'] ?? null,

            'position' => $validated['position'],
            'domain' => $validated['domain'],

            'education_level' =>
                $validated['education_level'] ?? null,

            'experience_years' =>
                $validated['experience_years'],

            'availability' =>
                $validated['availability'],

            'cv_path' => $cvPath,

            'cover_letter_path' =>
                $coverLetterPath,

            'message' =>
                $validated['message'] ?? null,

            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Réinitialisation
        |--------------------------------------------------------------------------
        */

        $this->reset([
            'position',
            'domain',
            'education_level',
            'experience_years',
            'availability',
            'cv',
            'cover_letter',
            'message',
        ]);

        $this->experience_years = 0;

        /*
        |--------------------------------------------------------------------------
        | Message de succès
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'success',
            'Votre candidature a été envoyée avec succès.'
        );
    }
};
?>

<div class="ak-job-page">

@section('title', 'Akatsuki - Demande d\'emploi')
{{-- ================================================================
     HERO
================================================================= --}}

<section class="ak-job-hero">

    <div class="container">

        <div class="ak-job-hero-content">

            <span class="ak-job-label">
                AKATSUKI CAREER
            </span>

            <h1>
                Construisez votre
                <span>avenir.</span>
            </h1>

            <p>
                Vous recherchez une opportunité professionnelle ?
                Déposez votre candidature et présentez-nous
                votre profil.
            </p>

        </div>

    </div>

</section>


{{-- ================================================================
     CONTENU
================================================================= --}}

<div class="container py-5">

    {{-- ============================================================
         MESSAGE DE SUCCÈS
    ============================================================= --}}

    @if (session('success'))

        <div class="ak-job-alert ak-job-alert-success">

            <div class="ak-job-alert-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div>
                <strong>Candidature envoyée</strong>

                <p>
                    {{ session('success') }}
                </p>
            </div>

        </div>

    @endif


    {{-- ============================================================
         ERREURS
    ============================================================= --}}

    @if ($errors->any())

        <div class="ak-job-alert ak-job-alert-error">

            <div class="ak-job-alert-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div>

                <strong>
                    Vérifiez les informations saisies
                </strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- ============================================================
         FORMULAIRE
    ============================================================= --}}

    <form
        wire:submit="submit"
        enctype="multipart/form-data"
    >

        {{-- ========================================================
             ÉTAPE 1 — INFORMATIONS PERSONNELLES
        ========================================================= --}}

        <section class="ak-job-card">

            <div class="ak-job-card-header">

                <div class="ak-job-icon">
                    <i class="bi bi-person"></i>
                </div>

                <div>

                    <span>ÉTAPE 01</span>

                    <h2>
                        Informations personnelles
                    </h2>

                    <p>
                        Renseignez vos coordonnées pour que
                        notre équipe puisse vous contacter.
                    </p>

                </div>

            </div>


            <div class="row g-4">

                {{-- NOM --}}
                <div class="col-md-6">

                    <label for="name">
                        Nom complet <span>*</span>
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-person"></i>

                        <input
                            id="name"
                            type="text"
                            wire:model="name"
                            placeholder="Votre nom complet"
                            autocomplete="name"
                        >

                    </div>

                    @error('name')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- EMAIL --}}
                <div class="col-md-6">

                    <label for="email">
                        Adresse email <span>*</span>
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-envelope"></i>

                        <input
                            id="email"
                            type="email"
                            wire:model="email"
                            placeholder="exemple@email.com"
                            autocomplete="email"
                        >

                    </div>

                    @error('email')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- TELEPHONE --}}
                <div class="col-md-6">

                    <label for="phone">
                        Numéro de téléphone <span>*</span>
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-telephone"></i>

                        <input
                            id="phone"
                            type="tel"
                            wire:model="phone"
                            placeholder="+229 XX XX XX XX"
                            autocomplete="tel"
                        >

                    </div>

                    @error('phone')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- SEXE --}}
                <div class="col-md-3">

                    <label for="gender">
                        Sexe
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-person-vcard"></i>

                        <select
                            id="gender"
                            wire:model="gender"
                        >

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

                </div>


                {{-- VILLE --}}
                <div class="col-md-3">

                    <label for="city">
                        Ville
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-geo-alt"></i>

                        <input
                            id="city"
                            type="text"
                            wire:model="city"
                            placeholder="Cotonou"
                        >

                    </div>

                </div>

            </div>

        </section>


        {{-- ========================================================
             ÉTAPE 2 — PROFIL PROFESSIONNEL
        ========================================================= --}}

        <section class="ak-job-card mt-4">

            <div class="ak-job-card-header">

                <div class="ak-job-icon">
                    <i class="bi bi-briefcase"></i>
                </div>

                <div>

                    <span>ÉTAPE 02</span>

                    <h2>
                        Profil professionnel
                    </h2>

                    <p>
                        Indiquez le poste et le domaine
                        qui correspondent à votre recherche.
                    </p>

                </div>

            </div>


            <div class="row g-4">

                {{-- POSTE --}}
                <div class="col-md-6">

                    <label for="position">
                        Poste recherché <span>*</span>
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-person-workspace"></i>

                        <input
                            id="position"
                            type="text"
                            wire:model="position"
                            placeholder="Ex : Développeur Laravel"
                        >

                    </div>

                    @error('position')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- DOMAINE --}}
                <div class="col-md-6">

                    <label for="domain">
                        Domaine <span>*</span>
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-grid"></i>

                        <select
                            id="domain"
                            wire:model="domain"
                        >

                            <option value="">
                                Sélectionner un domaine
                            </option>

                            <option value="informatique">
                                Informatique
                            </option>

                            <option value="commerce">
                                Commerce
                            </option>

                            <option value="marketing">
                                Marketing
                            </option>

                            <option value="finance">
                                Finance
                            </option>

                            <option value="administration">
                                Administration
                            </option>

                            <option value="communication">
                                Communication
                            </option>

                            <option value="logistique">
                                Logistique
                            </option>

                            <option value="ressources_humaines">
                                Ressources humaines
                            </option>

                            <option value="design">
                                Design
                            </option>

                            <option value="autre">
                                Autre
                            </option>

                        </select>

                    </div>

                    @error('domain')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- NIVEAU D'ÉTUDES --}}
                <div class="col-md-6">

                    <label for="education_level">
                        Niveau d'études
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-mortarboard"></i>

                        <select
                            id="education_level"
                            wire:model="education_level"
                        >

                            <option value="">
                                Sélectionner
                            </option>

                            <option value="secondaire">
                                Secondaire
                            </option>

                            <option value="bac">
                                BAC
                            </option>

                            <option value="bac+2">
                                BAC +2
                            </option>

                            <option value="bac+3">
                                BAC +3
                            </option>

                            <option value="bac+5">
                                BAC +5
                            </option>

                            <option value="doctorat">
                                Doctorat
                            </option>

                            <option value="autre">
                                Autre
                            </option>

                        </select>

                    </div>

                </div>


                {{-- EXPÉRIENCE --}}
                <div class="col-md-6">

                    <label for="experience_years">
                        Années d'expérience
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-clock-history"></i>

                        <input
                            id="experience_years"
                            type="number"
                            min="0"
                            max="60"
                            wire:model="experience_years"
                            placeholder="0"
                        >

                    </div>

                </div>


                {{-- DISPONIBILITÉ --}}
                <div class="col-12">

                    <label for="availability">
                        Disponibilité <span>*</span>
                    </label>

                    <div class="ak-job-input">

                        <i class="bi bi-calendar-check"></i>

                        <select
                            id="availability"
                            wire:model="availability"
                        >

                            <option value="">
                                Sélectionner votre disponibilité
                            </option>

                            <option value="immediate">
                                Disponible immédiatement
                            </option>

                            <option value="1_mois">
                                Disponible dans 1 mois
                            </option>

                            <option value="3_mois">
                                Disponible dans 3 mois
                            </option>

                            <option value="6_mois">
                                Disponible dans 6 mois
                            </option>

                        </select>

                    </div>

                    @error('availability')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </section>


        {{-- ========================================================
             ÉTAPE 3 — DOCUMENTS
        ========================================================= --}}

        <section class="ak-job-card mt-4">

            <div class="ak-job-card-header">

                <div class="ak-job-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div>

                    <span>ÉTAPE 03</span>

                    <h2>
                        Documents
                    </h2>

                    <p>
                        Ajoutez votre CV et votre lettre de motivation.
                    </p>

                </div>

            </div>


            <div class="row g-4">

                {{-- CV --}}
                <div class="col-md-6">

                    <label for="cv">
                        Curriculum Vitae <span>*</span>
                    </label>

                    <div class="ak-job-file">

                        <div class="ak-job-file-icon">
                            <i class="bi bi-file-earmark-person"></i>
                        </div>

                        <div class="ak-job-file-content">

                            <strong>
                                Télécharger votre CV
                            </strong>

                            <span>
                                PDF, DOC ou DOCX — 5 Mo maximum
                            </span>

                            <input
                                id="cv"
                                type="file"
                                wire:model="cv"
                                accept=".pdf,.doc,.docx"
                            >

                        </div>

                    </div>

                    @error('cv')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                    <div
                        wire:loading
                        wire:target="cv"
                        class="ak-job-uploading"
                    >
                        <i class="bi bi-arrow-repeat"></i>
                        Téléversement du CV...
                    </div>

                </div>


                {{-- LETTRE --}}
                <div class="col-md-6">

                    <label for="cover_letter">
                        Lettre de motivation
                    </label>

                    <div class="ak-job-file">

                        <div class="ak-job-file-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>

                        <div class="ak-job-file-content">

                            <strong>
                                Télécharger votre lettre
                            </strong>

                            <span>
                                PDF, DOC ou DOCX — 5 Mo maximum
                            </span>

                            <input
                                id="cover_letter"
                                type="file"
                                wire:model="cover_letter"
                                accept=".pdf,.doc,.docx"
                            >

                        </div>

                    </div>

                    @error('cover_letter')
                        <div class="ak-job-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </section>


        {{-- ========================================================
             ÉTAPE 4 — MESSAGE
        ========================================================= --}}

        <section class="ak-job-card mt-4">

            <div class="ak-job-card-header">

                <div class="ak-job-icon">
                    <i class="bi bi-chat-left-text"></i>
                </div>

                <div>

                    <span>ÉTAPE 04</span>

                    <h2>
                        Présentez votre candidature
                    </h2>

                    <p>
                        Expliquez-nous brièvement votre profil,
                        vos compétences et vos motivations.
                    </p>

                </div>

            </div>


            <label for="message">
                Message complémentaire
            </label>

            <textarea
                id="message"
                wire:model="message"
                rows="7"
                placeholder="Présentez brièvement votre profil, vos compétences, vos expériences et vos motivations..."
            ></textarea>

            @error('message')
                <div class="ak-job-error">
                    {{ $message }}
                </div>
            @enderror

        </section>


        {{-- ========================================================
             BAS DU FORMULAIRE
        ========================================================= --}}

        <div class="ak-job-submit mt-4">

            <a
                href="{{ route('services') }}"
                class="ak-job-back"
            >
                <i class="bi bi-arrow-left"></i>

                Retour aux services
            </a>


            <button
                type="submit"
                class="ak-job-button"
                wire:loading.attr="disabled"
                wire:target="submit"
            >

                <span wire:loading.remove wire:target="submit">

                    <i class="bi bi-send"></i>

                    Envoyer ma candidature

                </span>


                <span wire:loading wire:target="submit">

                    <i class="bi bi-arrow-repeat"></i>

                    Envoi en cours...

                </span>

            </button>

        </div>

    </form>

</div>


</div>
