<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Component;

new #[Layout('layouts.admin')]
#[Title('Clients - Administration')]
class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteClient(int $id): void
    {
        $user = User::findOrFail($id);

        // On évite de supprimer un utilisateur qui possède des commandes.
        if ($user->orders()->exists()) {
            session()->flash(
                'error',
                'Impossible de supprimer ce client car il possède des commandes.'
            );

            return;
        }

        $user->delete();

        session()->flash(
            'success',
            'Le client a été supprimé avec succès.'
        );
    }

    public function render()
    {
        $clients = User::query()
            ->withCount('orders')
            ->when(
                $this->search !== '',
                function ($query) {
                    $search = '%' . $this->search . '%';

                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', $search)
                            ->orWhere('email', 'like', $search)
                            ->orWhere('phone', 'like', $search);
                    });
                }
            )
            ->latest()
            ->paginate(10);

        return view('pages.admin.⚡clients', [
            'clients' => $clients,
        ]);
    }
};
?>

<div>

    <div class="container-fluid px-4 py-4">

        {{-- ========================================== --}}
        {{-- EN-TÊTE --}}
        {{-- ========================================== --}}

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4"
        >

            <div>

                <div class="small text-danger fw-bold mb-1">
                    ADMINISTRATION
                </div>

                <h1 class="fw-bold mb-1">
                    Clients
                </h1>

                <p class="text-muted mb-0">
                    Gérez les clients de votre boutique Akatsuki.
                </p>

            </div>


            <div>

                <span class="badge bg-dark px-3 py-2">

                    <i class="bi bi-people me-1"></i>

                    {{ $clients->total() }}
                    client(s)

                </span>

            </div>

        </div>


        {{-- ========================================== --}}
        {{-- MESSAGES --}}
        {{-- ========================================== --}}

        @if(session()->has('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @if(session()->has('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-exclamation-triangle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================== --}}
        {{-- RECHERCHE --}}
        {{-- ========================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-3">

                <div class="row g-3 align-items-center">

                    <div class="col-lg-8">

                        <div class="position-relative">

                            <i
                                class="bi bi-search position-absolute"
                                style="
                                    left:15px;
                                    top:50%;
                                    transform:translateY(-50%);
                                    color:#777;
                                "
                            ></i>

                            <input
                                type="search"
                                wire:model.live.debounce.400ms="search"
                                class="form-control ps-5"
                                placeholder="Rechercher par nom, email ou téléphone..."
                            >

                        </div>

                    </div>


                    <div class="col-lg-4 text-lg-end">

                        @if($search !== '')

                            <button
                                type="button"
                                wire:click="$set('search', '')"
                                class="btn btn-outline-secondary"
                            >

                                <i class="bi bi-x-lg me-1"></i>

                                Effacer

                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================== --}}
        {{-- TABLE CLIENTS --}}
        {{-- ========================================== --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="px-4">
                                    Client
                                </th>

                                <th>
                                    Téléphone
                                </th>

                                <th>
                                    Commandes
                                </th>

                                <th>
                                    Inscription
                                </th>

                                <th>
                                    Statut
                                </th>

                                <th class="text-end px-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($clients as $client)

                                <tr>

                                    {{-- CLIENT --}}

                                    <td class="px-4">

                                        <div
                                            class="d-flex align-items-center gap-3"
                                        >

                                            <div
                                                class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                style="
                                                    width:45px;
                                                    height:45px;
                                                    background:#fde8e8;
                                                    color:#e50914;
                                                "
                                            >

                                                {{ strtoupper(
                                                    substr($client->name ?? 'C', 0, 1)
                                                ) }}

                                            </div>


                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $client->name ?? 'Sans nom' }}

                                                </div>

                                                <small class="text-muted">

                                                    {{ $client->email }}

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- TELEPHONE --}}

                                    <td>

                                        @if($client->phone)

                                            <span>
                                                {{ $client->phone }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                Non renseigné
                                            </span>

                                        @endif

                                    </td>


                                    {{-- COMMANDES --}}

                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $client->orders_count }}

                                            commande(s)

                                        </span>

                                    </td>


                                    {{-- DATE --}}

                                    <td>

                                        <span>

                                            {{ $client->created_at?->format('d/m/Y') }}

                                        </span>

                                        <small class="text-muted d-block">

                                            {{ $client->created_at?->format('H:i') }}

                                        </small>

                                    </td>


                                    {{-- STATUT --}}

                                    <td>

                                        @if($client->email_verified_at)

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Vérifié

                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">

                                                <i class="bi bi-clock me-1"></i>

                                                Non vérifié

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTIONS --}}

                                    <td class="text-end px-4">

                                        <div
                                            class="d-flex justify-content-end gap-2"
                                        >

                                            {{-- VOIR --}}

                                            <a
                                                href="{{ route(
                                                    'admin.clients.show',
                                                    $client->id
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Voir le client"
                                            >

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            {{-- SUPPRIMER --}}

                                            @if($client->orders_count === 0)

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Supprimer"
                                                    wire:click="deleteClient({{ $client->id }})"
                                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce client ?"
                                                >

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            @else

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Ce client possède des commandes"
                                                    disabled
                                                >

                                                    <i class="bi bi-lock"></i>

                                                </button>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center py-5"
                                    >

                                        <i
                                            class="bi bi-people fs-1 text-muted d-block mb-3"
                                        ></i>

                                        <h5 class="fw-bold">
                                            Aucun client trouvé
                                        </h5>

                                        <p class="text-muted mb-0">

                                            @if($search)

                                                Aucun client ne correspond
                                                à votre recherche.

                                            @else

                                                Aucun client n'est encore enregistré.

                                            @endif

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- ====================================== --}}
                {{-- PAGINATION --}}
                {{-- ====================================== --}}

                @if($clients->hasPages())

                    <div class="p-4 border-top">

                        {{ $clients->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>