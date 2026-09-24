<?php

use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

// Accueil principal de la boutique.
Route::livewire('/', 'pages::⚡index')->name('home');

// Alias historiques conserves pour les anciens liens de navigation.
Route::redirect('/accueil', '/')->name('index');

// Pages de presentation et de navigation du catalogue.
Route::livewire('/catalogue', 'pages::⚡catalogue')->name('catalogue');
Route::livewire('/services', 'pages::⚡service')->name('services');
Route::livewire('/categories', 'pages::⚡categories')->name('categories');
Route::livewire('/produitDetail', 'pages::⚡produitDetail')->name('produitDetail');
Route::livewire('/panier', 'pages::⚡panier')->name('panier');
Route::livewire('/favoris', 'pages::⚡favoris')->name('favoris');
Route::livewire('/commande', 'pages::⚡commande')->name('commande');

Route::get('/produitDetail/{slug}', function ($slug) {
    return view('pages::⚡produitDetail', compact('slug'));
})->name('produit.show');