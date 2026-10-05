<header class="admin-header">


{{-- =====================================================
     PARTIE GAUCHE
====================================================== --}}

<div class="header-left">

    {{-- Bouton menu mobile --}}
    <button
        type="button"
        class="sidebar-toggle d-lg-none"
        id="sidebarToggle"
        aria-label="Ouvrir le menu"
        aria-controls="sidebar"
        aria-expanded="false"
    >
        <i class="bi bi-list"></i>
    </button>


    {{-- Titre de la page --}}
    <div class="header-title-wrapper">

        <div class="header-label">
            AKATSUKI ADMIN
        </div>

        <h1>
            @yield('page-title', 'Administration')
        </h1>

    </div>

</div>


{{-- =====================================================
     PARTIE DROITE
====================================================== --}}

<div class="header-right">

    {{-- Notifications --}}
@auth

    <livewire:admin.notifications />

@endauth


    {{-- Séparateur --}}
    <div class="header-separator"></div>


    {{-- =================================================
         PROFIL ADMIN
    ================================================== --}}

    @auth

        <div class="admin-profile">

            {{-- Avatar --}}
            <div class="admin-avatar">

                {{ strtoupper(
                    substr(
                        auth()->user()->name ?? 'A',
                        0,
                        1
                    )
                ) }}

            </div>


            {{-- Informations --}}
            <div class="admin-info">

                <strong>
                    {{ auth()->user()->name ?? 'Administrateur' }}
                </strong>

                <small>
                    Administrateur
                </small>

            </div>


            {{-- Menu profil --}}
            <div class="dropdown">

                <button
                    type="button"
                    class="profile-dropdown-btn"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="Menu administrateur"
                >
                    <i class="bi bi-chevron-down"></i>
                </button>


                <ul class="dropdown-menu dropdown-menu-end admin-profile-menu">

                    {{-- Tableau de bord --}}
                    <li>

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="dropdown-item"
                        >
                            <i class="bi bi-speedometer2"></i>

                            <span>
                                Tableau de bord
                            </span>
                        </a>

                    </li>


                    {{-- Paramètres --}}
                    <li>

                        <a
                            href="{{ route('admin.parametres') }}"
                            class="dropdown-item"
                        >
                            <i class="bi bi-gear"></i>

                            <span>
                                Paramètres
                            </span>
                        </a>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    {{-- Boutique --}}
                    <li>

                        <a
                            href="{{ route('index') }}"
                            class="dropdown-item"
                        >
                            <i class="bi bi-shop"></i>

                            <span>
                                Voir la boutique
                            </span>
                        </a>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    {{-- Déconnexion --}}
                    <li>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item logout-item"
                            >
                                <i class="bi bi-box-arrow-right"></i>

                                <span>
                                    Déconnexion
                                </span>
                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </div>

    @else

        {{-- Sécurité si personne n'est connecté --}}
        <a
            href="{{ route('login') }}"
            class="btn btn-dark"
        >
            <i class="bi bi-box-arrow-in-right me-1"></i>
            Connexion
        </a>

    @endauth

</div>


</header>
