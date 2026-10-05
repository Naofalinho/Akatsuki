<?php

use App\Models\JobOffer;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Offres d’emploi')] class extends Component
{
    public string $search = '';
    public string $category = '';
    public string $location = '';
    public string $contract = '';

    /**
     * Réinitialiser tous les filtres.
     */
    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'category',
            'location',
            'contract',
        ]);
    }

    /**
     * Récupérer les offres publiées.
     */
    public function getOffersProperty()
    {
        return JobOffer::query()
            ->where('status', 'published')

            ->when(
                trim($this->search) !== '',
                function ($query) {
                    $search = trim($this->search);

                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%")
                            ->orWhere('category', 'like', "%{$search}%")
                            ->orWhere('location', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
                }
            )

            ->when(
                $this->category !== '',
                function ($query) {
                    $query->where('category', $this->category);
                }
            )

            ->when(
                $this->location !== '',
                function ($query) {
                    $query->where('location', $this->location);
                }
            )

            ->when(
                $this->contract !== '',
                function ($query) {
                    $query->where('contract', $this->contract);
                }
            )

            ->latest()
            ->get();
    }

    /**
     * Liste des catégories disponibles.
     */
    public function getCategoriesProperty()
    {
        return JobOffer::query()
            ->where('status', 'published')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    /**
     * Liste des villes disponibles.
     */
    public function getLocationsProperty()
    {
        return JobOffer::query()
            ->where('status', 'published')
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');
    }
};
?>

<div class="ak-jobs-page">
    @section('title', 'Akatsuki - Offres d\'emploi')
    {{-- =========================================================
    HERO
    ========================================================== --}}
    <section class="ak-jobs-hero">

        <div class="ak-jobs-hero-overlay"></div>

        <div class="ak-jobs-hero-content">

            <span class="ak-jobs-label">
                AKATSUKI EMPLOI
            </span>

            <h1>
                Trouvez votre prochain
                <span>emploi.</span>
            </h1>

            <p>
                Découvrez les opportunités professionnelles disponibles
                et trouvez l'emploi qui correspond à votre profil.
            </p>

        </div>

    </section>


    {{-- =========================================================
    CONTENU PRINCIPAL
    ========================================================== --}}
    <section class="ak-jobs-content">

        <div class="ak-jobs-container">


            {{-- =================================================
            FILTRES
            ================================================== --}}
            <aside class="ak-jobs-filter">

                <div class="ak-jobs-filter-header">

                    <div>
                        <span class="ak-jobs-filter-label">
                            RECHERCHE
                        </span>

                        <h2>
                            Filtrer les offres
                        </h2>
                    </div>

                    <i class="bi bi-sliders"></i>

                </div>


                {{-- Recherche --}}
                <div class="ak-jobs-field-group">

                    <label for="search">
                        Rechercher
                    </label>

                    <div class="ak-jobs-input-wrapper">

                        <i class="bi bi-search"></i>

                        <input id="search" type="text" wire:model.live.debounce.400ms="search"
                            placeholder="Poste, entreprise..." class="ak-jobs-field">

                    </div>

                </div>


                {{-- Catégorie --}}
                <div class="ak-jobs-field-group">

                    <label for="category">
                        Catégorie
                    </label>

                    <select id="category" wire:model.live="category" class="ak-jobs-field">

                        <option value="">
                            Toutes les catégories
                        </option>

                        @foreach ($this->categories as $categoryItem)

                        <option value="{{ $categoryItem }}">
                            {{ $categoryItem }}
                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Localisation --}}
                <div class="ak-jobs-field-group">

                    <label for="location">
                        Localisation
                    </label>

                    <select id="location" wire:model.live="location" class="ak-jobs-field">

                        <option value="">
                            Toutes les villes
                        </option>

                        @foreach ($this->locations as $locationItem)

                        <option value="{{ $locationItem }}">
                            {{ $locationItem }}
                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Type de contrat --}}
                <div class="ak-jobs-field-group">

                    <label for="contract">
                        Type de contrat
                    </label>

                    <select id="contract" wire:model.live="contract" class="ak-jobs-field">

                        <option value="">
                            Tous les contrats
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

                </div>


                {{-- Réinitialiser --}}
                <button type="button" wire:click="resetFilters" class="ak-jobs-reset">

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Réinitialiser les filtres

                </button>

            </aside>



            {{-- =================================================
            LISTE DES OFFRES
            ================================================== --}}
            <div class="ak-jobs-results">


                {{-- En-tête résultats --}}
                <div class="ak-jobs-results-header">

                    <div>

                        <span class="ak-jobs-results-label">
                            OPPORTUNITÉS
                        </span>

                        <h2>
                            Offres disponibles
                        </h2>

                    </div>

                    <div class="ak-jobs-count">

                        {{ $this->offers->count() }}

                        {{ $this->offers->count() > 1 ? 'offres' : 'offre' }}

                    </div>

                </div>



                {{-- Loading --}}
                <div wire:loading wire:target="search,category,location,contract" class="ak-jobs-loading">

                    <i class="bi bi-arrow-repeat"></i>

                    Recherche en cours...

                </div>



                {{-- Liste --}}
                <div class="ak-jobs-list">

                    @forelse ($this->offers as $offer)

                    <article class="ak-job-offer" wire:key="job-offer-{{ $offer->id }}">

                        {{-- Icône --}}
                        <div class="ak-job-offer-icon">

                            <i class="bi bi-briefcase-fill"></i>

                        </div>


                        {{-- Contenu --}}
                        <div class="ak-job-offer-content">


                            {{-- Haut de carte --}}
                            <div class="ak-job-offer-top">

                                <div>

                                    <span class="ak-job-category">
                                        {{ $offer->category }}
                                    </span>

                                    <h3>
                                        {{ $offer->title }}
                                    </h3>

                                    <p class="ak-job-company">

                                        <i class="bi bi-building"></i>

                                        {{ $offer->company }}

                                    </p>

                                </div>


                                <span class="ak-job-contract">
                                    {{ $offer->contract }}
                                </span>

                            </div>



                            {{-- Description --}}
                            <p class="ak-job-description">

                                {{ $offer->description }}

                            </p>



                            {{-- Informations --}}
                            <div class="ak-job-meta">


                                {{-- Localisation --}}
                                @if ($offer->location)

                                <span>

                                    <i class="bi bi-geo-alt"></i>

                                    {{ $offer->location }}

                                </span>

                                @endif


                                {{-- Salaire --}}
                                @if ($offer->salary)

                                <span>

                                    <i class="bi bi-cash-stack"></i>

                                    {{ $offer->salary }}

                                </span>

                                @endif


                                {{-- Date limite --}}
                                @if ($offer->deadline)

                                <span>

                                    <i class="bi bi-calendar-event"></i>

                                    Jusqu'au
                                    {{ $offer->deadline->format('d/m/Y') }}

                                </span>

                                @endif


                            </div>



                            {{-- Actions --}}
                            <div class="ak-job-offer-actions">


                                {{-- Voir l'offre --}}
                                <a href="{{ route('offre.detail', ['id' => $offer->id]) }}"
                                    class="ak-job-button ak-job-button-details">
                                    <i class="bi bi-eye"></i>
                                    Voir l'offre
                                </a>


                                {{-- Postuler --}}
                                @auth

                                <a href="#" class="ak-job-button ak-job-button-apply" wire:click.prevent>

                                    <i class="bi bi-send"></i>

                                    Postuler

                                </a>

                                @else

                                <a href="{{ route('login') }}" class="ak-job-button ak-job-button-apply">

                                    <i class="bi bi-box-arrow-in-right"></i>

                                    Se connecter

                                </a>

                                @endauth


                            </div>

                        </div>

                    </article>

                    @empty

                    {{-- Aucun résultat --}}
                    <div class="ak-jobs-empty">

                        <div class="ak-jobs-empty-icon">

                            <i class="bi bi-search"></i>

                        </div>

                        <h3>
                            Aucune offre trouvée
                        </h3>

                        <p>
                            Aucune offre ne correspond à vos critères
                            de recherche.
                        </p>

                        <button type="button" wire:click="resetFilters" class="ak-job-button ak-job-button-apply">

                            <i class="bi bi-arrow-counterclockwise"></i>

                            Réinitialiser les filtres

                        </button>

                    </div>

                    @endforelse

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    CTA RECRUTEUR
    ========================================================== --}}
    <section class="ak-jobs-cta">

        <div class="ak-jobs-cta-content">

            <div>
                <span class="ak-jobs-label">
                    RECRUTEURS
                </span>

                <h2>
                    Vous recherchez de nouveaux talents ?
                </h2>

                <p>
                    Publiez votre offre d'emploi et trouvez les candidats
                    correspondant à vos besoins.
                </p>
            </div>

            <a href="{{ route('publier.offre') }}" class="ak-job-button ak-job-button-apply">
                <i class="bi bi-plus-circle"></i>
                Publier une offre
            </a>

        </div>

    </section>

</div>