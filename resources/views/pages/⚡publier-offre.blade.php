<?php

use App\Models\JobOffer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Publier une offre d’emploi')] class extends Component
{
    public string $title = '';
    public string $company = '';
    public string $category = '';
    public string $location = '';
    public string $contract = '';
    public string $salary = '';
    public string $description = '';
    public string $requirements = '';
    public string $benefits = '';
    public string $deadline = '';

    public function save(): void
    {
        $validated = $this->validate([
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'company' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'contract' => [
                'required',
                'string',
                'max:100',
            ],

            'salary' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
                'min:20',
            ],

            'requirements' => [
                'nullable',
                'string',
            ],

            'benefits' => [
                'nullable',
                'string',
            ],

            'deadline' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
        ]);

        JobOffer::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'company' => $validated['company'],
            'category' => $validated['category'],
            'location' => $validated['location'],
            'contract' => $validated['contract'],
            'salary' => $validated['salary'] ?: null,
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?: null,
            'benefits' => $validated['benefits'] ?: null,
            'deadline' => $validated['deadline'] ?: null,

            // L'offre doit être validée par l'administration.
            'status' => 'pending',
        ]);

        session()->flash(
            'success',
            'Votre offre a été envoyée avec succès. Elle sera publiée après validation.'
        );

        $this->reset([
            'title',
            'company',
            'category',
            'location',
            'contract',
            'salary',
            'description',
            'requirements',
            'benefits',
            'deadline',
        ]);
    }
};
?>

<div class="ak-publish-page">
    @section('title', 'Akatsuki - Publier une offre')
    {{-- HERO --}}
    <section class="ak-publish-hero">

        <div class="ak-publish-hero-overlay"></div>

        <div class="ak-publish-hero-content">

            <span class="ak-publish-label">
                AKATSUKI EMPLOI
            </span>

            <h1>
                Publiez votre
                <span>offre d'emploi.</span>
            </h1>

            <p>
                Présentez votre opportunité professionnelle et
                trouvez les talents dont votre entreprise a besoin.
            </p>

        </div>

    </section>


    {{-- CONTENU --}}
    <section class="ak-publish-content">

        <div class="ak-publish-container">

            {{-- MESSAGE DE SUCCÈS --}}
            @if (session('success'))

                <div class="ak-publish-success">

                    <i class="bi bi-check-circle-fill"></i>

                    <div>
                        <strong>
                            Offre envoyée
                        </strong>

                        <p>
                            {{ session('success') }}
                        </p>
                    </div>

                </div>

            @endif


            {{-- ERREURS --}}
            @if ($errors->any())

                <div class="ak-publish-errors">

                    <div class="ak-publish-error-title">

                        <i class="bi bi-exclamation-triangle-fill"></i>

                        Veuillez corriger les erreurs suivantes :

                    </div>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                wire:submit="save"
                class="ak-publish-form"
            >

                {{-- INFORMATIONS PRINCIPALES --}}
                <div class="ak-publish-card">

                    <div class="ak-publish-card-header">

                        <div class="ak-publish-card-icon">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>

                        <div>

                            <span>
                                ÉTAPE 01
                            </span>

                            <h2>
                                Informations sur le poste
                            </h2>

                        </div>

                    </div>


                    <div class="ak-publish-grid">


                        {{-- TITRE --}}
                        <div class="ak-publish-field ak-publish-field-full">

                            <label for="title">
                                Intitulé du poste
                                <span>*</span>
                            </label>

                            <input
                                id="title"
                                type="text"
                                wire:model="title"
                                placeholder="Ex : Développeur Laravel / PHP"
                            >

                            @error('title')
                                <small>{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- ENTREPRISE --}}
                        <div class="ak-publish-field">

                            <label for="company">
                                Entreprise
                                <span>*</span>
                            </label>

                            <input
                                id="company"
                                type="text"
                                wire:model="company"
                                placeholder="Nom de votre entreprise"
                            >

                            @error('company')
                                <small>{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- CATÉGORIE --}}
                        <div class="ak-publish-field">

                            <label for="category">
                                Catégorie
                                <span>*</span>
                            </label>

                            <select
                                id="category"
                                wire:model="category"
                            >

                                <option value="">
                                    Sélectionner une catégorie
                                </option>

                                <option value="Informatique">
                                    Informatique
                                </option>

                                <option value="Commerce">
                                    Commerce
                                </option>

                                <option value="Marketing">
                                    Marketing
                                </option>

                                <option value="Finance">
                                    Finance
                                </option>

                                <option value="Administration">
                                    Administration
                                </option>

                                <option value="Design">
                                    Design
                                </option>

                                <option value="Ressources humaines">
                                    Ressources humaines
                                </option>

                                <option value="Logistique">
                                    Logistique
                                </option>

                                <option value="Santé">
                                    Santé
                                </option>

                                <option value="Éducation">
                                    Éducation
                                </option>

                                <option value="Autre">
                                    Autre
                                </option>

                            </select>

                            @error('category')
                                <small>{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- LOCALISATION --}}
                        <div class="ak-publish-field">

                            <label for="location">
                                Localisation
                                <span>*</span>
                            </label>

                            <input
                                id="location"
                                type="text"
                                wire:model="location"
                                placeholder="Ex : Cotonou"
                            >

                            @error('location')
                                <small>{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- CONTRAT --}}
                        <div class="ak-publish-field">

                            <label for="contract">
                                Type de contrat
                                <span>*</span>
                            </label>

                            <select
                                id="contract"
                                wire:model="contract"
                            >

                                <option value="">
                                    Sélectionner
                                </option>

                                <option value="CDI">
                                    CDI
                                </option>

                                <option value="CDD">
                                    CDD
                                </option>

                                <option value="Freelance">
                                    Freelance
                                </option>

                                <option value="Stage">
                                    Stage
                                </option>

                                <option value="Intérim">
                                    Intérim
                                </option>

                            </select>

                            @error('contract')
                                <small>{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- SALAIRE --}}
                        <div class="ak-publish-field">

                            <label for="salary">
                                Salaire
                            </label>

                            <input
                                id="salary"
                                type="text"
                                wire:model="salary"
                                placeholder="Ex : 300 000 - 500 000 FCFA"
                            >

                            @error('salary')
                                <small>{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- DATE LIMITE --}}
                        <div class="ak-publish-field">

                            <label for="deadline">
                                Date limite de candidature
                            </label>

                            <input
                                id="deadline"
                                type="date"
                                wire:model="deadline"
                            >

                            @error('deadline')
                                <small>{{ $message }}</small>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- DESCRIPTION --}}
                <div class="ak-publish-card">

                    <div class="ak-publish-card-header">

                        <div class="ak-publish-card-icon">
                            <i class="bi bi-file-text-fill"></i>
                        </div>

                        <div>

                            <span>
                                ÉTAPE 02
                            </span>

                            <h2>
                                Description du poste
                            </h2>

                        </div>

                    </div>


                    <div class="ak-publish-field">

                        <label for="description">
                            Description
                            <span>*</span>
                        </label>

                        <textarea
                            id="description"
                            wire:model="description"
                            rows="7"
                            placeholder="Décrivez le poste, les missions et les responsabilités..."
                        ></textarea>

                        @error('description')
                            <small>{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- PROFIL --}}
                <div class="ak-publish-card">

                    <div class="ak-publish-card-header">

                        <div class="ak-publish-card-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>

                        <div>

                            <span>
                                ÉTAPE 03
                            </span>

                            <h2>
                                Profil recherché
                            </h2>

                        </div>

                    </div>


                    <div class="ak-publish-field">

                        <label for="requirements">
                            Compétences et exigences
                        </label>

                        <textarea
                            id="requirements"
                            wire:model="requirements"
                            rows="6"
                            placeholder="Ex : Bac+3, 2 ans d'expérience, maîtrise de Laravel..."
                        ></textarea>

                        @error('requirements')
                            <small>{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- AVANTAGES --}}
                <div class="ak-publish-card">

                    <div class="ak-publish-card-header">

                        <div class="ak-publish-card-icon">
                            <i class="bi bi-gift-fill"></i>
                        </div>

                        <div>

                            <span>
                                ÉTAPE 04
                            </span>

                            <h2>
                                Avantages
                            </h2>

                        </div>

                    </div>


                    <div class="ak-publish-field">

                        <label for="benefits">
                            Avantages proposés
                        </label>

                        <textarea
                            id="benefits"
                            wire:model="benefits"
                            rows="5"
                            placeholder="Ex : Assurance, primes, télétravail, formation..."
                        ></textarea>

                        @error('benefits')
                            <small>{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- INFORMATION --}}
                <div class="ak-publish-notice">

                    <i class="bi bi-info-circle-fill"></i>

                    <div>

                        <strong>
                            Validation de votre offre
                        </strong>

                        <p>
                            Après l'envoi, votre offre sera examinée par
                            l'équipe AKATSUKI avant d'être publiée.
                        </p>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="ak-publish-actions">

                    <a
                        href="{{ route('offre.emploi') }}"
                        class="ak-publish-cancel"
                    >
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="ak-publish-submit"
                        wire:loading.attr="disabled"
                    >

                        <span wire:loading.remove wire:target="save">

                            <i class="bi bi-send-fill"></i>

                            Envoyer l'offre

                        </span>

                        <span wire:loading wire:target="save">

                            <i class="bi bi-arrow-repeat"></i>

                            Envoi en cours...

                        </span>

                    </button>

                </div>

            </form>

        </div>

    </section>

</div>