<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#111111">
    <meta name="description" content="Administration de la boutique Akatsuki">

    <title>@yield('title', config('app.name', 'Akatsuki').' | Administration')</title>

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/setting.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/confirm-password.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/catalogue.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demande-emploi.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/panier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/offres-emploi.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/publier-offre.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/offre-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/commande-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/confirmation-commande.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/commande.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/favoris.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notification.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">

    @livewireStyles
</head>