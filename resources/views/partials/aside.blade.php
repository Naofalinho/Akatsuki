<aside class="sidebar" id="sidebar" aria-label="Navigation d’administration">

    {{-- Logo --}}
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            AKA<span>TSUKI</span>
        </a>

        <div class="brand-subtitle">
            ADMINISTRATION
        </div>
    </div>


    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <div class="sidebar-section-title">
            MENU PRINCIPAL
        </div>

        {{-- Dashboard --}}
        <a
            href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif
        >
            <span class="nav-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </span>

            <span>Dashboard</span>
        </a>


        {{-- Produits --}}
        <a
            href="{{ route('admin.produits') }}"
            class="nav-link {{ request()->routeIs('admin.produits') ? 'active' : '' }}"
            @if(request()->routeIs('admin.produits')) aria-current="page" @endif
        >
            <span class="nav-icon">
                <i class="bi bi-box-seam"></i>
            </span>

            <span>Produits</span>
        </a>


        {{-- Ajouter --}}
        <a
            href="{{ route('admin.produits.create') }}"
            class="nav-link {{ request()->routeIs('admin.produits.create') ? 'active' : '' }}"
            @if(request()->routeIs('admin.produits.create')) aria-current="page" @endif
        >
            <span class="nav-icon">
                <i class="bi bi-plus-square"></i>
            </span>

            <span>Ajouter un produit</span>
        </a>


        <div class="sidebar-section-title mt-4">
            VENTES
        </div>


        {{-- Commandes --}}
        <a
            href="{{ route('admin.commandes') }}"
            class="nav-link {{ request()->routeIs('admin.commandes') ? 'active' : '' }}"
            @if(request()->routeIs('admin.commandes')) aria-current="page" @endif
        >
            <span class="nav-icon">
                <i class="bi bi-bag-check"></i>
            </span>

            <span>Commandes</span>
        </a>


        {{-- Clients --}}
        <a
            href="{{ route('admin.clients') }}"
            class="nav-link {{ request()->routeIs('admin.clients') ? 'active' : '' }}"
            @if(request()->routeIs('admin.clients')) aria-current="page" @endif
        >
            <span class="nav-icon">
                <i class="bi bi-people"></i>
            </span>

            <span>Clients</span>
        </a>


        <div class="sidebar-section-title mt-4">
            GESTION
        </div>


        {{-- Stock --}}
        <a
            href="{{ route('admin.stock') }}"
            class="nav-link {{ request()->routeIs('admin.stock') ? 'active' : '' }}"
            @if(request()->routeIs('admin.stock')) aria-current="page" @endif
        >
            <span class="nav-icon">
                <i class="bi bi-boxes"></i>
            </span>

            <span>Stock</span>
        </a>


        {{-- Paramètres --}}
        <a
            href="{{ route('admin.parametres') }}"
            class="nav-link {{ request()->routeIs('admin.parametres') ? 'active' : '' }}"
            @if(request()->routeIs('admin.parametres')) aria-current="page" @endif
        >
            <span class="nav-icon">
                <i class="bi bi-gear"></i>
            </span>

            <span>Paramètres</span>
        </a>

    </nav>


    {{-- Bas de sidebar --}}
    <div class="sidebar-bottom">

        <a
            href="{{ route('home') }}"
            class="shop-link"
        >
            <span class="nav-icon">
                <i class="bi bi-shop"></i>
            </span>

            <span>Voir la boutique</span>
        </a>

    </div>

</aside>
