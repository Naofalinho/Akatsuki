<header class="admin-header">

    <div class="header-left">

        {{-- Bouton mobile --}}
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

        <div>
            <div class="header-label">
                AKATSUKI
            </div>

            <h1>@yield('page-title', 'Administration')</h1>
        </div>

    </div>


    <div class="header-right">

        {{-- Notifications --}}
        <button
            type="button"
            class="header-action"
            title="Notifications"
            aria-label="Notifications"
        >
            <i class="bi bi-bell"></i>

            <span class="notification-dot"></span>
        </button>


        {{-- Séparateur --}}
        <div class="header-separator"></div>


        {{-- Administrateur --}}
        <div class="admin-profile">

            <div class="admin-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div class="admin-info">

                <strong>
                    {{ auth()->user()->name ?? 'Administrateur' }}
                </strong>

                <small>
                    Administrateur
                </small>

            </div>

            <i class="bi bi-chevron-down profile-arrow"></i>

        </div>

    </div>

</header>
