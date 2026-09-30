<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <div class="ak-services-page">

{{-- HERO --}}
<section class="ak-services-hero">

    <div class="container">

        <div class="ak-services-hero-content">

            <span class="ak-services-label">
                AKATSUKI SERVICES
            </span>

            <h1>
                Des services pensés<br>
                <span>pour vous.</span>
            </h1>

            <p>
                Découvrez les services proposés par AKATSUKI :
                emploi, commerce, livraison, informatique,
                design et bien plus encore.
            </p>

            <div class="ak-services-hero-actions">

                <a href="#services" class="ak-services-btn">
                    <i class="bi bi-grid"></i>
                    Découvrir nos services
                </a>

                <a href="{{ route('home') }}" class="ak-services-btn-outline">
                    <i class="bi bi-shop"></i>
                    Visiter la boutique
                </a>

            </div>

        </div>

    </div>

</section>


{{-- SERVICES --}}
<section id="services" class="ak-services-section">

    <div class="container">

        {{-- HEADER --}}
        <div class="ak-section-heading">

            <div>

                <span class="ak-section-label">
                    NOS SERVICES
                </span>

                <h2>
                    Tout ce dont vous avez besoin
                </h2>

            </div>

            <p>
                AKATSUKI vous accompagne avec des services
                adaptés aux particuliers, candidats et entreprises.
            </p>

        </div>


        {{-- GRID --}}
        <div class="row g-4">


            {{-- DEMANDE D'EMPLOI --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card ak-service-featured">

                    <div class="ak-service-icon">
                        <i class="bi bi-person-workspace"></i>
                    </div>

                    <span class="ak-service-number">
                        01
                    </span>

                    <h3>
                        Demande d'emploi
                    </h3>

                    <p>
                        Vous recherchez un emploi ?
                        Déposez votre candidature et votre CV
                        pour trouver des opportunités correspondant
                        à votre profil.
                    </p>

                    <a
                        href="{{ route('demande.emploi') }}"
                        class="ak-service-link"
                    >
                        Déposer ma candidature
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- OFFRE D'EMPLOI --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card">

                    <div class="ak-service-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <span class="ak-service-number">
                        02
                    </span>

                    <h3>
                        Offre d'emploi
                    </h3>

                    <p>
                        Vous êtes une entreprise ?
                        Publiez vos offres d'emploi et trouvez
                        les profils correspondant à vos besoins.
                    </p>

                    <a
                        href="{{ route('offre.emploi') }}"
                        class="ak-service-link"
                    >
                        Publier une offre
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- BOUTIQUE --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card">

                    <div class="ak-service-icon">
                        <i class="bi bi-bag"></i>
                    </div>

                    <span class="ak-service-number">
                        03
                    </span>

                    <h3>
                        Boutique en ligne
                    </h3>

                    <p>
                        Découvrez notre catalogue de produits,
                        ajoutez vos articles favoris au panier
                        et passez facilement vos commandes.
                    </p>

                    <a
                        href="{{ route('catalogue') }}"
                        class="ak-service-link"
                    >
                        Voir le catalogue
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- LIVRAISON --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card">

                    <div class="ak-service-icon">
                        <i class="bi bi-truck"></i>
                    </div>

                    <span class="ak-service-number">
                        04
                    </span>

                    <h3>
                        Livraison
                    </h3>

                    <p>
                        Faites-vous livrer vos commandes
                        directement à l'adresse de votre choix
                        et suivez l'évolution de votre livraison.
                    </p>

                    <a
                        href="#"
                        class="ak-service-link"
                    >
                        En savoir plus
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- INFORMATIQUE --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card">

                    <div class="ak-service-icon">
                        <i class="bi bi-code-slash"></i>
                    </div>

                    <span class="ak-service-number">
                        05
                    </span>

                    <h3>
                        Services informatiques
                    </h3>

                    <p>
                        Création de sites web, applications,
                        maintenance informatique et solutions
                        numériques pour particuliers et entreprises.
                    </p>

                    <a
                        href="#"
                        class="ak-service-link"
                    >
                        Découvrir
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- DESIGN --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card">

                    <div class="ak-service-icon">
                        <i class="bi bi-palette"></i>
                    </div>

                    <span class="ak-service-number">
                        06
                    </span>

                    <h3>
                        Design & communication
                    </h3>

                    <p>
                        Création de logos, flyers, affiches,
                        bannières et supports de communication
                        adaptés à votre activité.
                    </p>

                    <a
                        href="#"
                        class="ak-service-link"
                    >
                        Demander un service
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- PUBLICITE --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card">

                    <div class="ak-service-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>

                    <span class="ak-service-number">
                        07
                    </span>

                    <h3>
                        Publicité
                    </h3>

                    <p>
                        Faites connaître votre entreprise,
                        vos produits ou vos services grâce
                        aux solutions publicitaires AKATSUKI.
                    </p>

                    <a
                        href="#"
                        class="ak-service-link"
                    >
                        Faire une publicité
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- FORMATION --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card">

                    <div class="ak-service-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <span class="ak-service-number">
                        08
                    </span>

                    <h3>
                        Formation
                    </h3>

                    <p>
                        Développez vos compétences grâce à des
                        formations adaptées aux besoins des étudiants,
                        professionnels et entrepreneurs.
                    </p>

                    <a
                        href="#"
                        class="ak-service-link"
                    >
                        Découvrir les formations
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            {{-- ENTREPRISE --}}
            <div class="col-md-6 col-lg-4">

                <div class="ak-service-card ak-service-dark">

                    <div class="ak-service-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>

                    <span class="ak-service-number">
                        09
                    </span>

                    <h3>
                        Espace entreprises
                    </h3>

                    <p>
                        Un espace dédié aux entreprises pour
                        présenter leurs activités, publier leurs
                        offres et développer leur visibilité.
                    </p>

                    <a
                        href="#"
                        class="ak-service-link"
                    >
                        Espace entreprise
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- CTA --}}
<section class="ak-services-cta">

    <div class="container">

        <div class="ak-services-cta-box">

            <div>

                <span>
                    UNE OPPORTUNITÉ VOUS INTÉRESSE ?
                </span>

                <h2>
                    Commencez dès maintenant.
                </h2>

                <p>
                    Déposez votre candidature ou publiez
                    une offre d'emploi sur AKATSUKI.
                </p>

            </div>

            <div class="ak-services-cta-actions">

                <a
                    href="{{ route('demande.emploi') }}"
                    class="ak-services-btn"
                >
                    <i class="bi bi-person-plus"></i>
                    Je cherche un emploi
                </a>

                <a
                    href="{{ route('offre.emploi') }}"
                    class="ak-services-btn-outline"
                >
                    <i class="bi bi-building-add"></i>
                    Je recrute
                </a>

            </div>

        </div>

    </div>

</section>

</div>

</div>