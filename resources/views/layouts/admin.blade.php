<!DOCTYPE html>
<html data-bs-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body style="width: auto;color: #212529;">
    <div class="admin-shell">
        <div class="row g-0">
            @include('partials.aside')
            <section class="col-lg-9 col-xl-10 main">
                @include('partials.header')
                {{ $slot }}

                @include('partials.footer')

                @include('partials.scripts')
            </section>
        </div>
    </div>
</body>

</html>