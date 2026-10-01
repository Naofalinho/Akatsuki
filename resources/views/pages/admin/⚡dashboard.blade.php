<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.admin')] class extends Component
{
    //
};
?>

<div>

    {{-- =========================
        EN-TÊTE
    ========================== --}}
    <div class="page-title mb-4">

        <div class="small text-danger fw-bold">
            ADMINISTRATION
        </div>

        <h1>
            Dashboard
        </h1>

        <p class="muted">
            Vue d'ensemble de votre activité.
        </p>

    </div>


    {{-- =========================
        STATISTIQUES
    ========================== --}}
    <div class="row g-3 mb-4">

        {{-- Chiffre d'affaires --}}
        <div class="col-sm-6 col-xl-3">

            <div class="card-admin stat h-100">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="muted">
                            Chiffre d'affaires
                        </small>

                        <h3 class="mt-1 mb-0">
                            2 480 000
                        </h3>

                        <small>
                            FCFA
                        </small>

                    </div>

                    <div class="icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Commandes --}}
        <div class="col-sm-6 col-xl-3">

            <div class="card-admin stat h-100">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="muted">
                            Commandes
                        </small>

                        <h3 class="mt-1 mb-0">
                            148
                        </h3>

                        <small class="text-success">
                            +12% ce mois
                        </small>

                    </div>

                    <div class="icon">
                        <i class="bi bi-bag"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Clients --}}
        <div class="col-sm-6 col-xl-3">

            <div class="card-admin stat h-100">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="muted">
                            Clients
                        </small>

                        <h3 class="mt-1 mb-0">
                            624
                        </h3>

                        <small class="text-success">
                            +8 nouveaux
                        </small>

                    </div>

                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Produits --}}
        <div class="col-sm-6 col-xl-3">

            <div class="card-admin stat h-100">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="muted">
                            Produits
                        </small>

                        <h3 class="mt-1 mb-0">
                            86
                        </h3>

                        <small class="text-danger">
                            6 stock faible
                        </small>

                    </div>

                    <div class="icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
        CONTENU PRINCIPAL
    ========================== --}}
    <div class="row g-4">

        {{-- =====================
            DERNIÈRES COMMANDES
        ====================== --}}
        <div class="col-xl-8">

            <div class="card-admin">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="mb-0">
                        Dernières commandes
                    </h5>

                    <a
                        href="{{ route('admin.commandes') }}"
                        class="btn btn-sm btn-outline-ak"
                    >
                        Tout voir
                    </a>

                </div>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>
                                <th>N°</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>
                                    #AK-0148
                                </td>

                                <td>
                                    Jean Kouassi
                                </td>

                                <td>
                                    125 000 FCFA
                                </td>

                                <td>
                                    <span class="badge-status status-paid">
                                        Payée
                                    </span>
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    #AK-0147
                                </td>

                                <td>
                                    Sarah Mensah
                                </td>

                                <td>
                                    89 500 FCFA
                                </td>

                                <td>
                                    <span class="badge-status status-pending">
                                        En attente
                                    </span>
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    #AK-0146
                                </td>

                                <td>
                                    David Ahoua
                                </td>

                                <td>
                                    210 000 FCFA
                                </td>

                                <td>
                                    <span class="badge-status status-delivered">
                                        Livrée
                                    </span>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================
            ACCÈS RAPIDE
        ====================== --}}
        <div class="col-xl-4">

            <div class="card-admin">

                <h5 class="mb-0">
                    Accès rapide
                </h5>


                <div class="d-grid gap-2 mt-3">

                    {{-- Ajouter un produit --}}
                    <a
                        href="{{ route('admin.produits.create') }}"
                        class="btn btn-ak"
                    >
                        <i class="bi bi-plus-lg me-2"></i>
                        Ajouter un produit
                    </a>


                    {{-- Stock --}}
                    <a
                        href="{{ route('admin.stock') }}"
                        class="btn btn-outline-ak"
                    >
                        <i class="bi bi-boxes me-2"></i>
                        Voir le stock
                    </a>


                    {{-- Clients --}}
                    <a
                        href="{{ route('admin.clients') }}"
                        class="btn btn-outline-dark"
                    >
                        <i class="bi bi-people me-2"></i>
                        Gérer les clients
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

