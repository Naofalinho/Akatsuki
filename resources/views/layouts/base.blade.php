<!DOCTYPE html>
<html data-bs-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head')

<body style="width: auto;color: #212529;">
    
    @include('partials.navbar')

    {{ $slot }}

    @include('partials.footer')

    @include('partials.scripts')

    <div
    x-data="{ show: false, message: '' }"
    x-on:cart-updated.window="
        message = $event.detail.message;
        show = true;
        setTimeout(() => show = false, 2500);
    "
    x-on:cart-error.window="
        message = $event.detail.message;
        show = true;
        setTimeout(() => show = false, 2500);
    "
    x-show="show"
    x-transition
    class="position-fixed bottom-0 end-0 p-4"
    style="z-index: 9999;"
>
    <div class="alert alert-dark shadow-lg rounded-pill mb-0">
        <i class="bi bi-check-circle me-2"></i>
        <span x-text="message"></span>
    </div>
</div>
</body>

</html>