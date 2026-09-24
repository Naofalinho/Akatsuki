<!-- NAVBAR AKATSUKI -->
<nav class="ak-navbar">
    <div class="container">

        <!-- Ligne principale -->
        <div class="navbar-main">

            <!-- Logo -->
            <a href="{{ route('index') }}" class="ak-logo">
                AKA<span>TSUKI</span>
            </a>


            <!-- Menu desktop -->
            <div class="ak-menu">

                <a
                    href="{{ route('index') }}"
                    class="{{ request()->routeIs('index') ? 'active' : '' }}"
                >
                    Accueil
                </a>

                <a
                    href="{{ route('catalogue') }}"
                    class="{{ request()->routeIs('catalogue') ? 'active' : '' }}"
                >
                    Catalogue
                </a>

                <a
                    href="{{ route('categories') }}"
                    class="{{ request()->routeIs('categories') ? 'active' : '' }}"
                >
                    Catégories
                </a>

                <a
                    href="{{ route('services') }}"
                    class="{{ request()->routeIs('services') ? 'active' : '' }}"
                >
                    Services
                </a>

            </div>


            <!-- Actions -->
            <div class="ak-actions">

                <!-- Recherche -->
                <form
                    class="d-flex mx-lg-auto searchbox"
                    action="{{ route('catalogue') }}"
                    method="GET"
                >
                    <input
                        class="form-control"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Rechercher un produit..."
                    >

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        <i class="bi bi-search"></i>
                    </button>
                </form>


                <!-- Favoris -->
                <a
                    href="{{ route('favoris') }}"
                    class="account-btn position-relative"
                    title="Favoris"
                >
                    <i class="bi bi-heart"></i>

                    <span
                        id="favCount"
                        class="badge bg-dark"
                    >
                        0
                    </span>
                </a>


                <!-- Panier Livewire -->
                <livewire:cart-counter />


                <!-- Compte -->
                <a
                    href="{{ route('login') }}"
                    class="account-btn"
                    title="Mon compte"
                >
                    <i class="bi bi-person"></i>
                    <span>Compte</span>
                </a>


                <!-- Bouton mobile -->
                <button
                    type="button"
                    class="mobile-toggle"
                    id="mobileToggle"
                    aria-label="Ouvrir le menu"
                >
                    <i class="bi bi-list"></i>
                </button>

            </div>

        </div>


        <!-- Barre de recherche secondaire -->
        <div
            class="search-container"
            id="searchContainer"
        >
            <form
                class="search-box"
                action="{{ route('catalogue') }}"
                method="GET"
            >

                <i class="bi bi-search"></i>

                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    id="productSearch"
                    placeholder="Rechercher un produit..."
                >

                <button type="submit">
                    Rechercher
                </button>

            </form>
        </div>


        <!-- Menu mobile -->
        <div
            class="mobile-menu"
            id="mobileMenu"
        >

            <a href="{{ route('index') }}">
                <i class="bi bi-house"></i>
                Accueil
            </a>


            <a href="{{ route('catalogue') }}">
                <i class="bi bi-grid"></i>
                Catalogue
            </a>


            <a href="{{ route('categories') }}">
                <i class="bi bi-tags"></i>
                Catégories
            </a>


            <a href="{{ route('services') }}">
                <i class="bi bi-fire"></i>
                Services
            </a>


            <a href="{{ route('favoris') }}">
                <i class="bi bi-heart"></i>
                Favoris

                <span
                    id="mobileFavCount"
                    class="badge bg-dark ms-auto"
                >
                    0
                </span>
            </a>


            <!-- Panier mobile -->
            <a
                href="{{ route('panier') }}"
                class="mobile-cart-link"
            >
                <i class="bi bi-cart4"></i>
                <span>Panier</span>
            </a>


            <!-- Compte -->
            <a href="{{ route('login') }}">
                <i class="bi bi-person"></i>
                Mon compte
            </a>

        </div>

    </div>
</nav>

