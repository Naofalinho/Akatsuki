<?php

use App\Models\JobOffer;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Détail de l’offre')] class extends Component
{
    public JobOffer $offer;

    public function mount(int $id): void
    {
        $this->offer = JobOffer::query()
            ->where('status', 'published')
            ->findOrFail($id);
    }
};
?>

<div class="ak-offer-detail-page">

    {{-- HERO --}}
    <section class="ak-offer-detail-hero">

        <div class="ak-offer-detail-hero-overlay"></div>

        <div class="ak-offer-detail-hero-content">

            <a
                href="{{ route('offre.emploi') }}"
                class="ak-offer-back"
            >
                <i class="bi bi-arrow-left"></i>
                Retour aux offres
            </a>

            <div class="ak-offer-category">
                {{ $offer->category }}
            </div>

            <h1>
                {{ $offer->title }}
            </h1>

            <div class="ak-offer-company">

                <i class="bi bi-building"></i>

                <span>
                    {{ $offer->company }}
                </span>

            </div>

        </div>

    </section>


    {{-- CONTENU --}}
    <section class="ak-offer-detail-content">

        <div class="ak-offer-detail-container">

            {{-- COLONNE PRINCIPALE --}}
            <main class="ak-offer-main">

                {{-- INFORMATIONS --}}
                <div class="ak-offer-info-grid">

                    <div class="ak-offer-info-item">

                        <i class="bi bi-geo-alt-fill"></i>

                        <div>
                            <span>Localisation</span>
                            <strong>
                                {{ $offer->location }}
                            </strong>
                        </div>

                    </div>


                    <div class="ak-offer-info-item">

                        <i class="bi bi-briefcase-fill"></i>

                        <div>
                            <span>Contrat</span>
                            <strong>
                                {{ $offer->contract }}
                            </strong>
                        </div>

                    </div>


                    @if ($offer->salary)

                        <div class="ak-offer-info-item">

                            <i class="bi bi-cash-stack"></i>

                            <div>
                                <span>Salaire</span>
                                <strong>
                                    {{ $offer->salary }}
                                </strong>
                            </div>

                        </div>

                    @endif


                    @if ($offer->deadline)

                        <div class="ak-offer-info-item">

                            <i class="bi bi-calendar-event-fill"></i>

                            <div>
                                <span>Date limite</span>
                                <strong>
                                    {{ $offer->deadline->format('d/m/Y') }}
                                </strong>
                            </div>

                        </div>

                    @endif

                </div>


                {{-- DESCRIPTION --}}
                <article class="ak-offer-section">

                    <div class="ak-offer-section-title">

                        <span>01</span>

                        <h2>
                            Description du poste
                        </h2>

                    </div>

                    <div class="ak-offer-text">

                        {!! nl2br(e($offer->description)) !!}

                    </div>

                </article>


                {{-- EXIGENCES --}}
                @if ($offer->requirements)

                    <article class="ak-offer-section">

                        <div class="ak-offer-section-title">

                            <span>02</span>

                            <h2>
                                Profil recherché
                            </h2>

                        </div>

                        <div class="ak-offer-text">

                            {!! nl2br(e($offer->requirements)) !!}

                        </div>

                    </article>

                @endif


                {{-- AVANTAGES --}}
                @if ($offer->benefits)

                    <article class="ak-offer-section">

                        <div class="ak-offer-section-title">

                            <span>03</span>

                            <h2>
                                Avantages
                            </h2>

                        </div>

                        <div class="ak-offer-text">

                            {!! nl2br(e($offer->benefits)) !!}

                        </div>

                    </article>

                @endif

            </main>


            {{-- SIDEBAR --}}
            <aside class="ak-offer-sidebar">

                <div class="ak-offer-apply-card">

                    <div class="ak-offer-apply-icon">

                        <i class="bi bi-send-fill"></i>

                    </div>

                    <h2>
                        Intéressé par cette offre ?
                    </h2>

                    <p>
                        Envoyez votre candidature et donnez une nouvelle
                        chance à votre parcours professionnel.
                    </p>


                    @auth

                        <a
                            href="#"
                            class="ak-offer-apply-button"
                        >
                            <i class="bi bi-send"></i>
                            Postuler maintenant
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="ak-offer-apply-button"
                        >
                            <i class="bi bi-box-arrow-in-right"></i>
                            Se connecter pour postuler
                        </a>

                    @endauth

                </div>


                {{-- ENTREPRISE --}}
                <div class="ak-offer-company-card">

                    <span>
                        ENTREPRISE
                    </span>

                    <h3>
                        {{ $offer->company }}
                    </h3>

                    <div>
                        <i class="bi bi-geo-alt"></i>
                        {{ $offer->location }}
                    </div>

                </div>


                {{-- DATE --}}
                <div class="ak-offer-date-card">

                    <i class="bi bi-clock-history"></i>

                    <div>

                        <span>
                            Offre publiée
                        </span>

                        <strong>
                            {{ $offer->created_at->diffForHumans() }}
                        </strong>

                    </div>

                </div>

            </aside>

        </div>

    </section>

</div>