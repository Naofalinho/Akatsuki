<!DOCTYPE html>
<html data-bs-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body class="admin-body">

    <div class="admin-shell">

        @include('partials.aside')

        <button class="sidebar-backdrop" id="sidebarBackdrop" type="button" aria-label="Fermer le menu"></button>

        <main class="main">
            @include('partials.header')

            <div class="admin-content">
                {{ $slot }}
            </div>

            @include('partials.scripts')

        </main>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (!sidebar || !toggle || !backdrop) {
                return;
            }

            const closeSidebar = () => {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('sidebar-open');
            };

            toggle.addEventListener('click', () => {
                const isOpen = sidebar.classList.toggle('show');
                backdrop.classList.toggle('show', isOpen);
                toggle.setAttribute('aria-expanded', String(isOpen));
                document.body.classList.toggle('sidebar-open', isOpen);
            });

            backdrop.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
        })();
    </script>

</body>

</html>