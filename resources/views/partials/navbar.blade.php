<!-- =========================================================
     NAVBAR AKATSUKI
========================================================= -->

<nav class="ak-navbar">

    <div class="container">

        <!-- LIGNE PRINCIPALE -->
        <div class="navbar-main">

            <!-- LOGO -->
            <a href="{{ route('index') }}" class="ak-logo">
                AKA<span>TSUKI</span>
            </a>

            <!-- MENU DESKTOP -->
            <div class="ak-menu">

                <a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'active' : '' }}">
                    Accueil
                </a>

                <a href="{{ route('catalogue') }}" class="{{ request()->routeIs('catalogue') ? 'active' : '' }}">
                    Catalogue
                </a>

                <a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}">
                    Catégories
                </a>

                <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">
                    Services
                </a>

            </div>

            <!-- ACTIONS -->
            <div class="ak-actions">

                <!-- RECHERCHE DESKTOP -->
                <form class="ak-search" action="{{ route('catalogue') }}" method="GET">

                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit..."
                        aria-label="Rechercher un produit">

                    <button type="submit" aria-label="Rechercher">
                        <i class="bi bi-search"></i>
                    </button>

                </form>

                <!-- FAVORIS -->
                <a href="{{ route('favoris') }}" class="account-btn ak-favorite-btn position-relative" title="Favoris"
                    aria-label="Favoris">

                    <i class="bi bi-heart"></i>

                    <livewire:favorite-counter key="favorite-desktop" />

                </a>

                <!-- PANIER -->
                <livewire:cart-counter key="cart-desktop" />

                <!-- COMPTE DESKTOP -->
                @auth

                <div class="ak-user-dropdown">

                    <button type="button" class="account-btn ak-user-toggle" id="userAccountToggle"
                        aria-label="Mon compte" aria-expanded="false">

                        <span class="ak-user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>

                        <span class="ak-user-name">
                            {{ auth()->user()->name }}
                        </span>

                        <i class="bi bi-chevron-down ak-chevron"></i>

                    </button>

                    <div class="ak-user-menu" id="userAccountMenu">

                        <div class="ak-user-info">

                            <div class="ak-user-avatar ak-user-avatar-large">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div class="ak-user-details">
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ auth()->user()->email }}</small>
                            </div>

                        </div>

                        <div class="ak-user-divider"></div>

                        <a href="{{ route('profile.edit') }}">
                            <i class="bi bi-person"></i>
                            <span>Mon compte</span>
                        </a>

                        <a href="{{ route('mes-commandes') }}" class="btn-ak-primary">
                            <i class="bi bi-bag"></i>
                            Voir mes commandes
                        </a>

                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Administration</span>
                        </a>
                        @endif

                        <a href="{{ route('favoris') }}">
                            <i class="bi bi-heart"></i>
                            <span>Mes favoris</span>
                        </a>

                        <div class="ak-user-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="ak-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>

                    </div>

                </div>

                @else

                <a href="{{ route('login') }}" class="account-btn" title="Connexion" aria-label="Connexion">

                    <i class="bi bi-person"></i>
                    <span>Connexion</span>

                </a>

                @endauth

                <!-- BURGER -->
                <button type="button" class="mobile-toggle" id="mobileToggle" aria-label="Ouvrir le menu"
                    aria-controls="mobileMenu" aria-expanded="false">

                    <i class="bi bi-list"></i>

                </button>

            </div>

        </div>

        <!-- MENU MOBILE -->
        <div class="mobile-menu" id="mobileMenu">

            <!-- RECHERCHE MOBILE -->
            <div class="ak-search-mobile">

                <form class="ak-search" action="{{ route('catalogue') }}" method="GET">

                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit..."
                        aria-label="Rechercher un produit">

                    <button type="submit" aria-label="Rechercher">
                        <i class="bi bi-search"></i>
                    </button>

                </form>

            </div>

            <a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'active' : '' }}">
                <i class="bi bi-house"></i>
                <span>Accueil</span>
            </a>

            <a href="{{ route('catalogue') }}" class="{{ request()->routeIs('catalogue') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Catalogue</span>
            </a>

            <a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Catégories</span>
            </a>

            <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">
                <i class="bi bi-fire"></i>
                <span>Services</span>
            </a>

            <a href="{{ route('favoris') }}" class="{{ request()->routeIs('favoris') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>Favoris</span>
                <span class="mobile-counter">
                    <livewire:favorite-counter key="favorite-mobile" />
                </span>
            </a>

            <a href="{{ route('panier') }}" class="{{ request()->routeIs('panier') ? 'active' : '' }}">
                <i class="bi bi-cart4"></i>
                <span>Panier</span>
            </a>

            <!-- COMPTE MOBILE -->
            @auth

            <button type="button" class="mobile-account-toggle" id="mobileAccountToggle" aria-expanded="false"
                aria-controls="mobileAccountMenu">

                <i class="bi bi-person-circle"></i>

                <span>{{ auth()->user()->name }}</span>

                <i class="bi bi-chevron-down mobile-account-chevron"></i>

            </button>

            <div class="mobile-account-menu" id="mobileAccountMenu">

                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    <span>Mon compte</span>
                </a>

                <a href="{{ route('mes-commandes') }}" class="{{ request()->routeIs('mes-commandes') ? 'active' : '' }}">
                    <i class="bi bi-bag"></i>
                    <span>Mes commandes</span>
                </a>

                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Administration</span>
                </a>
                @endif

                <a href="{{ route('favoris') }}" class="{{ request()->routeIs('favoris') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i>
                    <span>Mes favoris</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mobile-logout-form">

                    @csrf

                    <button type="submit">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Déconnexion</span>
                    </button>

                </form>

            </div>

            @else

            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>Connexion</span>
            </a>

            @endauth

        </div>

    </div>

</nav>

<script>
    (function () {

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function setBurgerState(open) {
        const toggle = document.getElementById('mobileToggle');
        const menu   = document.getElementById('mobileMenu');

        if (!toggle || !menu) return;

        menu.classList.toggle('show', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');

        const icon = toggle.querySelector('i');
        if (icon) {
            icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
        }
    }

    function closeMobileAccountMenu() {
        const subMenu = document.getElementById('mobileAccountMenu');
        const toggle  = document.getElementById('mobileAccountToggle');

        if (!subMenu || !toggle) return;

        subMenu.classList.remove('show');
        toggle.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
    }

    function closeUserMenu() {
        const dropdown = document.querySelector('.ak-user-dropdown');
        const toggle   = document.getElementById('userAccountToggle');

        if (!dropdown || !toggle) return;

        dropdown.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
    }

    /*
    |--------------------------------------------------------------------------
    | Initialisation (idempotente)
    |--------------------------------------------------------------------------
    */

    let listenersAttached = false;

    function attachListeners() {

        if (listenersAttached) return;
        listenersAttached = true;

        /*
        |--------------------------------------------------------------------------
        | Burger + fermeture extérieure (délégation)
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function (event) {

            if (event.target.closest('#mobileToggle')) {
                event.preventDefault();
                event.stopPropagation();

                const menu = document.getElementById('mobileMenu');
                if (menu) {
                    setBurgerState(!menu.classList.contains('show'));
                }
                return;
            }

            const menu   = document.getElementById('mobileMenu');
            const toggle = document.getElementById('mobileToggle');

            if (
                menu &&
                toggle &&
                menu.classList.contains('show') &&
                !menu.contains(event.target) &&
                !toggle.contains(event.target)
            ) {
                setBurgerState(false);
            }

        });

        /*
        |--------------------------------------------------------------------------
        | Fermer le menu mobile après clic sur un lien
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function (event) {

            const link = event.target.closest('#mobileMenu a');

            if (link) {
                setBurgerState(false);
                closeMobileAccountMenu();
            }

        });

        /*
        |--------------------------------------------------------------------------
        | Dropdown compte desktop
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function (event) {

            const toggle = event.target.closest('#userAccountToggle');
            const dropdown = document.querySelector('.ak-user-dropdown');

            if (toggle && dropdown) {

                event.preventDefault();
                event.stopPropagation();

                const isOpen = dropdown.classList.contains('open');

                dropdown.classList.toggle('open', !isOpen);
                toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');

                return;
            }

            if (
                dropdown &&
                !dropdown.contains(event.target)
            ) {
                closeUserMenu();
            }

        });

        /*
        |--------------------------------------------------------------------------
        | Dropdown compte mobile
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function (event) {

            const toggle  = event.target.closest('#mobileAccountToggle');
            const subMenu = document.getElementById('mobileAccountMenu');

            if (toggle && subMenu) {

                event.preventDefault();
                event.stopPropagation();

                const isOpen = subMenu.classList.contains('show');

                subMenu.classList.toggle('show', !isOpen);
                toggle.classList.toggle('active', !isOpen);
                toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');

                return;
            }

            if (
                subMenu &&
                subMenu.classList.contains('show') &&
                !subMenu.contains(event.target)
            ) {
                closeMobileAccountMenu();
            }

        });

        // Fermer le sous-menu après clic sur un lien
        document.addEventListener('click', function (event) {

            if (event.target.closest('#mobileAccountMenu a')) {
                closeMobileAccountMenu();
                setBurgerState(false);
            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Lancement
    |--------------------------------------------------------------------------
    */

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attachListeners);
    } else {
        attachListeners();
    }

    // Ré-init après navigation Livewire (wire:navigate)
    document.addEventListener('livewire:navigated', function () {
        attachListeners();
    });

})();
</script>