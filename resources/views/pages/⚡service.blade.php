<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Services')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $service = '';
    public string $message = '';

    public function demanderService(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:8', 'max:30'],
            'service' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $this->reset([
            'name',
            'email',
            'phone',
            'service',
            'message',
        ]);

        session()->flash(
            'service_success',
            'Votre demande a bien été enregistrée. Notre équipe vous contactera prochainement.'
        );
    }
};
?>

<div class="ak-services-page">
@section('title', 'Akatsuki - Services')
    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="ak-service-hero">

        <div class="ak-service-hero-content">

            <span class="ak-service-eyebrow">
                <i class="bi bi-stars"></i>
                NOS SERVICES
            </span>

            <h1>
                Des solutions
                <span>pour vos projets.</span>
            </h1>

            <p>
                AKATSUKI vous accompagne avec des services modernes
                en informatique, développement, design, publicité,
                livraison, formation, emploi et commerce.
            </p>

            <div class="ak-service-hero-actions">

                <a href="#nos-services"
                   class="ak-service-btn ak-service-btn-primary">
                    Découvrir nos services
                    <i class="bi bi-arrow-down"></i>
                </a>

                <a href="{{ route('catalogue') }}"
                   class="ak-service-btn ak-service-btn-outline">
                    Visiter la boutique
                    <i class="bi bi-shop"></i>
                </a>

            </div>

        </div>


        <div class="ak-service-hero-visual">

            <div class="ak-service-hero-card">

                <div class="ak-service-hero-icon">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>

                <div>
                    <strong>AKATSUKI</strong>
                    <span>
                        Services & boutique
                    </span>
                </div>

            </div>


            <div class="ak-floating-card ak-floating-card-1">
                <i class="bi bi-code-slash"></i>
                <span>Développement</span>
            </div>


            <div class="ak-floating-card ak-floating-card-2">
                <i class="bi bi-palette"></i>
                <span>Design</span>
            </div>


            <div class="ak-floating-card ak-floating-card-3">
                <i class="bi bi-shop"></i>
                <span>Boutique</span>
            </div>

        </div>

    </section>


    {{-- =========================================================
        INTRODUCTION
    ========================================================== --}}
    <section class="ak-service-intro" id="nos-services">

        <div class="ak-section-heading">

            <span class="ak-section-label">
                CE QUE NOUS PROPOSONS
            </span>

            <h2>
                Des services pensés pour
                <span>vos besoins.</span>
            </h2>

            <p>
                Que vous soyez particulier, étudiant, entrepreneur,
                entreprise ou professionnel, AKATSUKI propose des
                solutions adaptées à vos projets.
            </p>

        </div>

    </section>


    {{-- =========================================================
        BOUTIQUE
    ========================================================== --}}
    <section class="ak-service-shop">

        <div class="ak-service-shop-content">

            <span class="ak-section-label">
                BOUTIQUE AKATSUKI
            </span>

            <h2>
                Découvrez notre
                <span>boutique.</span>
            </h2>

            <p>
                Retrouvez tous nos produits directement dans le
                catalogue AKATSUKI : mode, technologie, maison,
                beauté et bien plus encore.
            </p>

            <div class="ak-service-shop-features">

                <div class="ak-shop-feature">

                    <div class="ak-shop-feature-icon">
                        <i class="bi bi-bag"></i>
                    </div>

                    <div>
                        <strong>
                            Produits variés
                        </strong>

                        <span>
                            Découvrez nos différentes catégories.
                        </span>
                    </div>

                </div>


                <div class="ak-shop-feature">

                    <div class="ak-shop-feature-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>

                    <div>
                        <strong>
                            Commande en ligne
                        </strong>

                        <span>
                            Ajoutez vos produits au panier facilement.
                        </span>
                    </div>

                </div>


                <div class="ak-shop-feature">

                    <div class="ak-shop-feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>

                    <div>
                        <strong>
                            Livraison
                        </strong>

                        <span>
                            Faites-vous livrer vos commandes.
                        </span>
                    </div>

                </div>

            </div>


            <div class="ak-service-shop-actions">

                <a href="{{ route('catalogue') }}"
                   class="ak-service-btn ak-service-btn-primary">

                    Accéder au catalogue

                    <i class="bi bi-arrow-right"></i>

                </a>

                <a href="{{ route('categories') }}"
                   class="ak-service-btn ak-service-btn-dark">

                    Voir les catégories

                    <i class="bi bi-grid"></i>

                </a>

            </div>

        </div>


        <div class="ak-service-shop-visual">

            <div class="ak-shop-main-card">

                <div class="ak-shop-main-icon">
                    <i class="bi bi-shop-window"></i>
                </div>

                <span>
                    AKATSUKI
                </span>

                <strong>
                    SHOP
                </strong>

                <small>
                    Mode • Tech • Maison • Beauté
                </small>

                <a href="{{ route('catalogue') }}">
                    Acheter maintenant
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>


            <div class="ak-shop-floating ak-shop-floating-1">
                <i class="bi bi-heart-fill"></i>
                <span>Favoris</span>
            </div>


            <div class="ak-shop-floating ak-shop-floating-2">
                <i class="bi bi-cart3"></i>
                <span>Panier</span>
            </div>

        </div>

    </section>


    {{-- =========================================================
        SERVICES INFORMATIQUES
    ========================================================== --}}
    <section class="ak-service-section">

        <div class="ak-service-section-header">

            <div>

                <span class="ak-section-label">
                    INFORMATIQUE
                </span>

                <h2>
                    Assistance & services informatiques
                </h2>

            </div>

            <p>
                Nous vous aidons à installer, réparer, sécuriser
                et maintenir vos équipements informatiques.
            </p>

        </div>


        <div class="ak-services-grid">

            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-pc-display"></i>
                </div>

                <h3>
                    Maintenance informatique
                </h3>

                <p>
                    Diagnostic, entretien et maintenance de vos
                    ordinateurs et équipements informatiques.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <h3>
                    Réparation ordinateur
                </h3>

                <p>
                    Assistance pour les pannes matérielles,
                    problèmes logiciels et dysfonctionnements.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-wifi"></i>
                </div>

                <h3>
                    Réseaux & Wi-Fi
                </h3>

                <p>
                    Installation, configuration et optimisation
                    de réseaux locaux et connexions Wi-Fi.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-window-stack"></i>
                </div>

                <h3>
                    Installation logiciels
                </h3>

                <p>
                    Installation et configuration de logiciels
                    et outils nécessaires à votre activité.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>

                <h3>
                    Sécurité informatique
                </h3>

                <p>
                    Conseils et solutions pour mieux protéger
                    vos appareils et vos données.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-cloud-arrow-up"></i>
                </div>

                <h3>
                    Données & sauvegarde
                </h3>

                <p>
                    Sauvegarde, organisation et récupération
                    de vos données importantes.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

        </div>

    </section>


    {{-- =========================================================
        DEVELOPPEMENT
    ========================================================== --}}
    <section class="ak-service-section ak-service-section-dark">

        <div class="ak-service-section-header">

            <div>

                <span class="ak-section-label">
                    DÉVELOPPEMENT
                </span>

                <h2>
                    Créons vos solutions numériques
                </h2>

            </div>

            <p>
                Nous développons des solutions modernes pour
                améliorer votre présence et votre activité en ligne.
            </p>

        </div>


        <div class="ak-services-grid ak-services-grid-development">

            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-globe2"></i>
                </div>

                <h3>
                    Sites vitrines
                </h3>

                <p>
                    Création de sites professionnels pour présenter
                    votre entreprise, vos services ou votre activité.
                </p>

                <a href="#demander-service">
                    Démarrer un projet
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-cart3"></i>
                </div>

                <h3>
                    Boutiques e-commerce
                </h3>

                <p>
                    Création de boutiques en ligne pour vendre
                    vos produits et gérer vos commandes.
                </p>

                <a href="#demander-service">
                    Démarrer un projet
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-phone"></i>
                </div>

                <h3>
                    Applications web
                </h3>

                <p>
                    Développement d'applications web modernes
                    adaptées à vos besoins professionnels.
                </p>

                <a href="#demander-service">
                    Démarrer un projet
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-kanban"></i>
                </div>

                <h3>
                    Solutions métiers
                </h3>

                <p>
                    Développement de solutions personnalisées
                    pour faciliter la gestion de votre activité.
                </p>

                <a href="#demander-service">
                    Démarrer un projet
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

        </div>

    </section>


    {{-- =========================================================
        LIVRAISON
    ========================================================== --}}
    <section class="ak-service-feature">

        <div class="ak-service-feature-content">

            <span class="ak-section-label">
                LIVRAISON
            </span>

            <h2>
                Vos produits,
                <span>livrés simplement.</span>
            </h2>

            <p>
                AKATSUKI propose un service de livraison pour
                faciliter l'acheminement de vos commandes,
                documents et colis.
            </p>

            <ul class="ak-service-list">

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Livraison de commandes
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Livraison de colis
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Livraison de documents
                </li>

                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    Livraison pour particuliers et entreprises
                </li>

            </ul>

            <a href="#demander-service"
               class="ak-service-btn ak-service-btn-primary">

                Demander une livraison

                <i class="bi bi-truck"></i>

            </a>

        </div>


        <div class="ak-service-feature-visual">

            <div class="ak-service-big-icon">
                <i class="bi bi-truck"></i>
            </div>

            <div class="ak-feature-badge">

                <strong>
                    RAPIDE
                </strong>

                <span>
                    Simple & pratique
                </span>

            </div>

        </div>

    </section>


    {{-- =========================================================
        DESIGN GRAPHIQUE
    ========================================================== --}}
    <section class="ak-service-section">

        <div class="ak-service-section-header">

            <div>

                <span class="ak-section-label">
                    CRÉATION
                </span>

                <h2>
                    Design graphique
                </h2>

            </div>

            <p>
                Donnez une identité professionnelle à votre
                entreprise, votre marque ou votre projet.
            </p>

        </div>


        <div class="ak-services-grid">

            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-palette"></i>
                </div>

                <h3>
                    Logo
                </h3>

                <p>
                    Création de logos modernes et adaptés
                    à votre identité.
                </p>

                <a href="#demander-service">
                    Demander un design
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-file-earmark-image"></i>
                </div>

                <h3>
                    Flyer & affiche
                </h3>

                <p>
                    Création de supports visuels pour vos
                    événements, promotions et activités.
                </p>

                <a href="#demander-service">
                    Demander un design
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <h3>
                    Carte de visite
                </h3>

                <p>
                    Création de cartes de visite professionnelles
                    pour votre activité.
                </p>

                <a href="#demander-service">
                    Demander un design
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-instagram"></i>
                </div>

                <h3>
                    Visuels réseaux sociaux
                </h3>

                <p>
                    Création de publications et visuels adaptés
                    aux réseaux sociaux.
                </p>

                <a href="#demander-service">
                    Demander un design
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-brush"></i>
                </div>

                <h3>
                    Identité visuelle
                </h3>

                <p>
                    Création d'une identité visuelle cohérente
                    pour votre marque.
                </p>

                <a href="#demander-service">
                    Demander un design
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-megaphone"></i>
                </div>

                <h3>
                    Supports publicitaires
                </h3>

                <p>
                    Création de supports graphiques destinés
                    à vos campagnes publicitaires.
                </p>

                <a href="#demander-service">
                    Demander un design
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

        </div>

    </section>


    {{-- =========================================================
        PUBLICITE
    ========================================================== --}}
    <section class="ak-service-section ak-service-section-light">

        <div class="ak-service-section-header">

            <div>

                <span class="ak-section-label">
                    COMMUNICATION
                </span>

                <h2>
                    Publicité & communication
                </h2>

            </div>

            <p>
                Développez votre visibilité grâce à des solutions
                publicitaires adaptées à votre activité.
            </p>

        </div>


        <div class="ak-services-grid">

            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>

                <h3>
                    Publicité digitale
                </h3>

                <p>
                    Création de campagnes et contenus publicitaires
                    pour développer votre visibilité en ligne.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-share"></i>
                </div>

                <h3>
                    Communication réseaux sociaux
                </h3>

                <p>
                    Création de contenus et supports pour
                    communiquer efficacement avec votre audience.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-bullseye"></i>
                </div>

                <h3>
                    Promotion
                </h3>

                <p>
                    Mise en place de supports promotionnels
                    pour présenter vos produits et services.
                </p>

                <a href="#demander-service">
                    Demander ce service
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

        </div>

    </section>


    {{-- =========================================================
        ENTREPRISES
    ========================================================== --}}
    <section class="ak-service-section ak-service-section-dark">

        <div class="ak-service-section-header">

            <div>

                <span class="ak-section-label">
                    ENTREPRISES
                </span>

                <h2>
                    Solutions pour professionnels
                </h2>

            </div>

            <p>
                Des solutions adaptées aux entreprises,
                associations et professionnels.
            </p>

        </div>


        <div class="ak-services-grid">

            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-pc-display-horizontal"></i>
                </div>

                <h3>
                    Équipement
                </h3>

                <p>
                    Accompagnement dans le choix et la mise
                    en place de vos équipements informatiques.
                </p>

                <a href="#demander-service">
                    Nous contacter
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-hdd-network"></i>
                </div>

                <h3>
                    Infrastructure
                </h3>

                <p>
                    Installation et organisation de votre
                    infrastructure informatique.
                </p>

                <a href="#demander-service">
                    Nous contacter
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-cloud"></i>
                </div>

                <h3>
                    Cloud
                </h3>

                <p>
                    Solutions de stockage et d'organisation
                    de vos données numériques.
                </p>

                <a href="#demander-service">
                    Nous contacter
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card ak-service-card-dark">

                <div class="ak-service-card-icon">
                    <i class="bi bi-headset"></i>
                </div>

                <h3>
                    Assistance
                </h3>

                <p>
                    Support et accompagnement pour vos besoins
                    informatiques et numériques.
                </p>

                <a href="#demander-service">
                    Nous contacter
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

        </div>

    </section>


    {{-- =========================================================
        FORMATION
    ========================================================== --}}
    <section class="ak-service-section">

        <div class="ak-service-section-header">

            <div>

                <span class="ak-section-label">
                    FORMATION
                </span>

                <h2>
                    Développez vos compétences
                </h2>

            </div>

            <p>
                Des formations pratiques pour mieux maîtriser
                les outils numériques.
            </p>

        </div>


        <div class="ak-services-grid">

            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-laptop"></i>
                </div>

                <h3>
                    Initiation informatique
                </h3>

                <p>
                    Apprenez les bases de l'utilisation
                    d'un ordinateur.
                </p>

                <a href="#demander-service">
                    Demander une formation
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <h3>
                    Bureautique
                </h3>

                <p>
                    Apprentissage des outils bureautiques
                    indispensables.
                </p>

                <a href="#demander-service">
                    Demander une formation
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-code-square"></i>
                </div>

                <h3>
                    Développement web
                </h3>

                <p>
                    Apprenez les bases du développement
                    de sites et applications web.
                </p>

                <a href="#demander-service">
                    Demander une formation
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>


            <article class="ak-service-card">

                <div class="ak-service-card-icon">
                    <i class="bi bi-phone-flip"></i>
                </div>

                <h3>
                    Outils numériques
                </h3>

                <p>
                    Apprenez à utiliser efficacement les outils
                    numériques pour vos activités.
                </p>

                <a href="#demander-service">
                    Demander une formation
                    <i class="bi bi-arrow-right"></i>
                </a>

            </article>

        </div>

    </section>


    {{-- =========================================================
        EMPLOI
    ========================================================== --}}
    <section class="ak-service-employment">

        <div class="ak-service-employment-content">

            <span class="ak-section-label">
                EMPLOI
            </span>

            <h2>
                Construisez votre
                <span>avenir professionnel.</span>
            </h2>

            <p>
                Consultez les offres disponibles, envoyez votre
                demande d'emploi ou publiez une offre pour trouver
                les talents dont vous avez besoin.
            </p>

            <div class="ak-employment-actions">

                <a href="{{ route('offre.emploi') }}"
                   class="ak-service-btn ak-service-btn-primary">

                    Voir les offres

                    <i class="bi bi-search"></i>

                </a>


                <a href="{{ route('demande.emploi') }}"
                   class="ak-service-btn ak-service-btn-outline">

                    Demande d'emploi

                    <i class="bi bi-file-earmark-person"></i>

                </a>


                @auth

                    <a href="{{ route('publier.offre') }}"
                       class="ak-service-btn ak-service-btn-outline">

                        Publier une offre

                        <i class="bi bi-plus-circle"></i>

                    </a>

                @else

                    <a href="{{ route('login') }}"
                       class="ak-service-btn ak-service-btn-outline">

                        Publier une offre

                        <i class="bi bi-plus-circle"></i>

                    </a>

                @endauth

            </div>

        </div>


        <div class="ak-service-employment-icon">

            <i class="bi bi-briefcase-fill"></i>

        </div>

    </section>


    {{-- =========================================================
        FORMULAIRE DE DEMANDE
    ========================================================== --}}
    <section class="ak-service-request"
             id="demander-service">

        <div class="ak-service-request-header">

            <span class="ak-section-label">
                BESOIN D'UN SERVICE ?
            </span>

            <h2>
                Parlons de votre
                <span>projet.</span>
            </h2>

            <p>
                Remplissez le formulaire ci-dessous et notre équipe
                vous contactera pour discuter de votre besoin.
            </p>

        </div>


        @if (session()->has('service_success'))

            <div class="ak-service-alert ak-service-alert-success">

                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('service_success') }}
                </span>

            </div>

        @endif


        <form wire:submit="demanderService"
              class="ak-service-form">

            <div class="ak-service-form-grid">

                {{-- NOM --}}
                <div class="ak-form-group">

                    <label for="name">
                        Nom complet
                    </label>

                    <div class="ak-input-wrapper">

                        <i class="bi bi-person"></i>

                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            placeholder="Votre nom complet"
                        >

                    </div>

                    @error('name')
                        <span class="ak-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- EMAIL --}}
                <div class="ak-form-group">

                    <label for="email">
                        Adresse e-mail
                    </label>

                    <div class="ak-input-wrapper">

                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            placeholder="exemple@email.com"
                        >

                    </div>

                    @error('email')
                        <span class="ak-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- TELEPHONE --}}
                <div class="ak-form-group">

                    <label for="phone">
                        Numéro de téléphone
                    </label>

                    <div class="ak-input-wrapper">

                        <i class="bi bi-telephone"></i>

                        <input
                            type="text"
                            id="phone"
                            wire:model="phone"
                            placeholder="+229 XX XX XX XX"
                        >

                    </div>

                    @error('phone')
                        <span class="ak-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- SERVICE --}}
                <div class="ak-form-group">

                    <label for="service">
                        Service souhaité
                    </label>

                    <div class="ak-input-wrapper">

                        <i class="bi bi-grid"></i>

                        <select
                            id="service"
                            wire:model="service"
                        >

                            <option value="">
                                Sélectionnez un service
                            </option>

                            <optgroup label="Boutique">

                                <option value="boutique">
                                    Boutique AKATSUKI
                                </option>

                                <option value="catalogue">
                                    Acheter un produit
                                </option>

                                <option value="livraison">
                                    Livraison
                                </option>

                            </optgroup>


                            <optgroup label="Informatique">

                                <option value="maintenance">
                                    Maintenance informatique
                                </option>

                                <option value="reparation">
                                    Réparation ordinateur
                                </option>

                                <option value="reseau">
                                    Réseaux & Wi-Fi
                                </option>

                                <option value="logiciel">
                                    Installation logiciels
                                </option>

                                <option value="securite">
                                    Sécurité informatique
                                </option>

                                <option value="sauvegarde">
                                    Données & sauvegarde
                                </option>

                            </optgroup>


                            <optgroup label="Développement">

                                <option value="site-vitrine">
                                    Site vitrine
                                </option>

                                <option value="e-commerce">
                                    Boutique e-commerce
                                </option>

                                <option value="application-web">
                                    Application web
                                </option>

                                <option value="solution-metier">
                                    Solution métier
                                </option>

                            </optgroup>


                            <optgroup label="Design">

                                <option value="design">
                                    Design graphique
                                </option>

                                <option value="logo">
                                    Création de logo
                                </option>

                                <option value="flyer">
                                    Flyer & affiche
                                </option>

                                <option value="carte-visite">
                                    Carte de visite
                                </option>

                                <option value="reseaux-sociaux">
                                    Visuels réseaux sociaux
                                </option>

                                <option value="identite-visuelle">
                                    Identité visuelle
                                </option>

                            </optgroup>


                            <optgroup label="Publicité">

                                <option value="publicite">
                                    Publicité & communication
                                </option>

                                <option value="publicite-digitale">
                                    Publicité digitale
                                </option>

                                <option value="promotion">
                                    Promotion
                                </option>

                            </optgroup>


                            <optgroup label="Entreprise">

                                <option value="equipement">
                                    Équipement informatique
                                </option>

                                <option value="infrastructure">
                                    Infrastructure
                                </option>

                                <option value="cloud">
                                    Cloud
                                </option>

                                <option value="assistance">
                                    Assistance informatique
                                </option>

                            </optgroup>


                            <optgroup label="Formation">

                                <option value="formation">
                                    Formation
                                </option>

                            </optgroup>


                            <option value="autre">
                                Autre demande
                            </option>

                        </select>

                    </div>

                    @error('service')
                        <span class="ak-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>


            {{-- MESSAGE --}}
            <div class="ak-form-group">

                <label for="message">
                    Décrivez votre besoin
                </label>

                <textarea
                    id="message"
                    wire:model="message"
                    rows="7"
                    placeholder="Expliquez-nous votre projet ou votre besoin..."
                ></textarea>

                @error('message')
                    <span class="ak-form-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>


            <div class="ak-service-form-footer">

                <p>
                    <i class="bi bi-shield-check"></i>
                    Vos informations restent confidentielles.
                </p>

                <button
                    type="submit"
                    class="ak-service-btn ak-service-btn-primary"
                >

                    <span
                        wire:loading.remove
                        wire:target="demanderService"
                    >
                        Envoyer ma demande
                        <i class="bi bi-send"></i>
                    </span>

                    <span
                        wire:loading
                        wire:target="demanderService"
                    >
                        Envoi en cours...
                        <i class="bi bi-hourglass-split"></i>
                    </span>

                </button>

            </div>

        </form>

    </section>


    {{-- =========================================================
        CTA FINAL
    ========================================================== --}}
    <section class="ak-service-final-cta">

        <div>

            <span class="ak-section-label">
                AKATSUKI
            </span>

            <h2>
                Une idée ?
                <span>Un projet ?</span>
            </h2>

            <p>
                Besoin d'un service ou envie de découvrir nos
                produits ? AKATSUKI est là pour vous accompagner.
            </p>

        </div>


        <div class="ak-service-final-actions">

            <a href="{{ route('catalogue') }}"
               class="ak-service-btn ak-service-btn-light">

                Visiter la boutique

                <i class="bi bi-shop"></i>

            </a>

            <a href="#demander-service"
               class="ak-service-btn ak-service-btn-outline">

                Nous contacter

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </section>

</div>