<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#111111">
    <meta name="description" content="Administration de la boutique Akatsuki">
    <title>@yield('title', config('app.name', 'Akatsuki').' | Administration')</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/setting.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/confirm-password.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/services.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/catalogue.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demande-emploi.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/offres-emploi.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/publier-offre.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/offre-detail.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/commande-detail.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/favoris.css') }}" />
    @livewireStyles
</head>


{{-- <meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance --}}
