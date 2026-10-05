<?php

use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {Route::view('admin/dashboard', 'dashboard')->name('dashboard');});

require __DIR__ . '/settings.php';

// Accueil principal de la boutique.
Route::livewire('/', 'pages::⚡index')->name('home');

// Alias historiques conserves pour les anciens liens de navigation.
Route::redirect('/accueil', '/')->name('index');

// Pages de presentation et de navigation du catalogue.
Route::livewire('/catalogue', 'pages::⚡catalogue')->name('catalogue');
Route::livewire('/services', 'pages::⚡service')->name('services');
Route::livewire('/categories', 'pages::⚡categories')->name('categories');
//Route::livewire('/produit-detail', 'pages::⚡produitDetail')->name('produitDetail');
Route::livewire('/panier', 'pages::⚡panier')->name('panier');
Route::livewire('/favoris', 'pages::⚡favoris')->name('favoris');
Route::livewire('/commande', 'pages::⚡commande')->name('commande');
Route::livewire('/produit-detail/{slug}', 'pages::⚡produit-detail')->name('produitDetail');
Route::livewire('/service', 'pages::⚡service')->name('services');
Route::livewire('/demande-emploi', 'pages::⚡demande-emploi')->name('demande.emploi');
Route::livewire('/offres-emploi', 'pages::⚡offres-emploi')->name('offre.emploi');
Route::livewire('/livraison', 'pages::⚡livraison')->name('livraison');

//les routes pour les offres d'emploi
Route::middleware(['auth'])->group(function () {
    Route::livewire( '/publier-offre','pages::⚡publier-offre')->name('publier.offre');});

Route::livewire('/offres-emploi/{id}','pages::⚡offre-detail')->name('offre.detail');

Route::middleware('auth')->group(function () {
    Route::livewire('/livraison/{order}','pages::⚡livraison')->name('livraison');});

// ==========================================
// COMMANDE
// ==========================================
Route::middleware('auth')->group(function () {
    Route::livewire(
        '/commande',
        'pages::⚡commande'
    )->name('commande');
});

// ==========================================
// MES COMMANDES
// ==========================================
Route::middleware('auth')->group(function () {

    // Liste des commandes
    Route::livewire(
        '/mes-commandes',
        'pages::⚡mes-commandes'
    )->name('mes-commandes');

    // Détail d'une commande
    Route::livewire(
        '/mes-commandes/{order}',
        'pages::⚡commande-detail'
    )->name('commande.detail');
});
/*
|--------------------------------------------------------------------------
| CONFIRMATION DE COMMANDE
|--------------------------------------------------------------------------
*/

Route::livewire(
    '/commande/{orderNumber}/confirmation',
    'pages::⚡confirmation-commande'
)->name('commandes.confirmation');

/*
|--------------------------------------------------------------------------
| ADMINISTRATION AKATSUKI
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard
        Route::livewire('/', 'pages::admin.⚡dashboard')
            ->name('dashboard');

        // Produits
        Route::livewire('/produits', 'pages::admin.⚡produits')
            ->name('produits');

        // Ajouter un produit
        Route::livewire('/produits/create', 'pages::admin.⚡ajouter-produit')
            ->name('produits.create');

        Route::livewire('/produits/{product}/edit', 'pages::admin.⚡modifier-produit')
            ->name('produits.edit');

        Route::livewire('/produits/{product}', 'pages::admin.⚡voir-produit')
            ->name('produits.show');

        // Commandes
        Route::livewire('/commandes', 'pages::admin.⚡commandes')
            ->name('commandes');

        Route::livewire(
            '/commandes/{order}',
            'pages::admin.⚡voir-commande'
        )->name('commandes.show');

        // Clients
        Route::livewire('/clients', 'pages::admin.⚡clients')
            ->name('clients');

        Route::livewire('/clients/{user}', 'pages::admin.⚡voir-client')
            ->name('clients.show');

        // Stock
        Route::livewire('/stock', 'pages::admin.⚡stock')
            ->name('stock');

        // Paramètres
        Route::livewire('/parametres', 'pages::admin.⚡parametres')
            ->name('parametres');
    });
