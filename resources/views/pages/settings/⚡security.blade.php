<?php

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Sécurité - AKATSUKI')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset(
                'current_password',
                'password',
                'password_confirmation'
            );

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset(
            'current_password',
            'password',
            'password_confirmation'
        );

        Flux::toast(
            variant: 'success',
            text: 'Votre mot de passe a été mis à jour avec succès.'
        );
    }
};
?>

<div class="ak-security-page">

    {{-- HEADER --}}
    <div class="security-header">
        <div>
            <span class="security-kicker">AKATSUKI</span>

            <h1>
                Sécurité du compte
            </h1>

            <p>
                Gérez votre mot de passe et protégez votre compte.
            </p>
        </div>

        <div class="security-header-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
    </div>


    {{-- CONTENU --}}
    <div class="security-container">

        {{-- NAVIGATION --}}
        <aside class="security-sidebar">

            <div class="security-user">

                <div class="security-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div>
                    <strong>
                        {{ Auth::user()->name }}
                    </strong>

                    <span>
                        {{ Auth::user()->email }}
                    </span>
                </div>

            </div>


            <nav class="security-menu">

                <a href="{{ route('profile.edit') }}">
                    <i class="bi bi-person"></i>
                    <span>Mon profil</span>
                </a>

                <a href="{{ route('panier') }}">
                    <i class="bi bi-bag"></i>
                    <span>Mon panier</span>
                </a>

                <a href="{{ route('favoris') }}">
                    <i class="bi bi-heart"></i>
                    <span>Mes favoris</span>
                </a>

                <a href="{{ route('security.edit') }}" class="active">
                    <i class="bi bi-shield-lock"></i>
                    <span>Sécurité</span>
                </a>

            </nav>


            <div class="security-sidebar-bottom">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="security-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        Déconnexion
                    </button>
                </form>

            </div>

        </aside>


        {{-- MAIN --}}
        <main class="security-content">

            {{-- MOT DE PASSE --}}
            <section class="security-card">

                <div class="security-card-header">

                    <div class="security-card-icon">
                        <i class="bi bi-key-fill"></i>
                    </div>

                    <div>
                        <h2>
                            Modifier le mot de passe
                        </h2>

                        <p>
                            Utilisez un mot de passe long et difficile à deviner
                            pour protéger votre compte.
                        </p>
                    </div>

                </div>


                <form
                    wire:submit="updatePassword"
                    class="security-form"
                >

                    {{-- ANCIEN MOT DE PASSE --}}
                    <div class="security-field">

                        <label for="current_password">
                            Mot de passe actuel
                        </label>

                        <div class="security-input">

                            <i class="bi bi-lock"></i>

                            <input
                                id="current_password"
                                type="password"
                                wire:model="current_password"
                                autocomplete="current-password"
                                placeholder="Votre mot de passe actuel"
                                required
                            />

                        </div>

                        @error('current_password')
                            <span class="security-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- NOUVEAU MOT DE PASSE --}}
                    <div class="security-field">

                        <label for="password">
                            Nouveau mot de passe
                        </label>

                        <div class="security-input">

                            <i class="bi bi-shield-lock"></i>

                            <input
                                id="password"
                                type="password"
                                wire:model="password"
                                autocomplete="new-password"
                                placeholder="Votre nouveau mot de passe"
                                required
                            />

                        </div>

                        @error('password')
                            <span class="security-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- CONFIRMATION --}}
                    <div class="security-field">

                        <label for="password_confirmation">
                            Confirmer le nouveau mot de passe
                        </label>

                        <div class="security-input">

                            <i class="bi bi-check2-circle"></i>

                            <input
                                id="password_confirmation"
                                type="password"
                                wire:model="password_confirmation"
                                autocomplete="new-password"
                                placeholder="Confirmez votre nouveau mot de passe"
                                required
                            />

                        </div>

                        @error('password_confirmation')
                            <span class="security-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- BOUTON --}}
                    <div class="security-form-footer">

                        <button
                            type="submit"
                            class="btn-ak-security"
                        >
                            <i class="bi bi-shield-check"></i>
                            Mettre à jour le mot de passe
                        </button>

                    </div>

                </form>

            </section>


            {{-- CONSEILS --}}
            <section class="security-card security-tips">

                <div class="security-card-header">

                    <div class="security-card-icon">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>

                    <div>
                        <h2>
                            Conseils de sécurité
                        </h2>

                        <p>
                            Quelques recommandations pour protéger votre compte AKATSUKI.
                        </p>
                    </div>

                </div>


                <div class="security-tips-list">

                    <div class="security-tip">

                        <span class="tip-icon">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <div>
                            <strong>
                                Utilisez un mot de passe unique
                            </strong>

                            <p>
                                Évitez d'utiliser le même mot de passe sur plusieurs sites.
                            </p>
                        </div>

                    </div>


                    <div class="security-tip">

                        <span class="tip-icon">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <div>
                            <strong>
                                Choisissez un mot de passe suffisamment long
                            </strong>

                            <p>
                                Mélangez lettres, chiffres et caractères spéciaux.
                            </p>
                        </div>

                    </div>


                    <div class="security-tip">

                        <span class="tip-icon">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <div>
                            <strong>
                                Ne partagez jamais votre mot de passe
                            </strong>

                            <p>
                                L'équipe AKATSUKI ne vous demandera jamais votre mot de passe.
                            </p>
                        </div>

                    </div>

                </div>

            </section>

        </main>

    </div>

</div>



{{-- 

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Title;
use Livewire\Component;
use Laravel\Passkeys\Actions\DeletePasskey;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

new #[Title('Security settings')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $canManageTwoFactor;

    public bool $twoFactorEnabled;

    public bool $requiresConfirmation;

    #[Locked]
    public bool $canManagePasskeys;

    #[Locked]
    public array $passkeys = [];

    public bool $showDeleteModal = false;

    #[Locked]
    public ?int $deletingPasskeyId = null;

    #[Locked]
    public string $deletingPasskeyName = '';

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
                $disableTwoFactorAuthentication(auth()->user());
            }

            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
            $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
        }

        $this->canManagePasskeys = Features::canManagePasskeys();

        if ($this->canManagePasskeys) {
            $this->loadPasskeys();
        }
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Password updated.'));
    }

    /**
     * Load the user's passkeys.
     */
    public function loadPasskeys(): void
    {
        $this->passkeys = auth()->user()->passkeys()
            ->select(['id', 'name', 'credential', 'created_at', 'last_used_at'])
            ->latest()
            ->get()
            ->map(fn ($passkey) => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'authenticator' => $passkey->authenticator,
                'created_at_diff' => $passkey->created_at->diffForHumans(),
                'last_used_at_diff' => $passkey->last_used_at?->diffForHumans(),
            ])
            ->toArray();
    }

    /**
     * Show the delete confirmation modal.
     */
    public function confirmDelete(int $passkeyId): void
    {
        $passkey = auth()->user()->passkeys()->findOrFail($passkeyId);

        $this->deletingPasskeyId = $passkey->id;
        $this->deletingPasskeyName = $passkey->name;
        $this->showDeleteModal = true;
    }

    /**
     * Delete the passkey.
     */
    public function deletePasskey(DeletePasskey $deletePasskey): void
    {
        if (! $this->deletingPasskeyId) {
            return;
        }

        $passkey = auth()->user()->passkeys()->findOrFail($this->deletingPasskeyId);

        $deletePasskey(auth()->user(), $passkey);

        $this->closeDeleteModal();
        $this->loadPasskeys();
    }

    /**
     * Close the delete confirmation modal.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingPasskeyId = null;
        $this->deletingPasskeyName = '';
    }

    /**
     * Handle the two-factor authentication enabled event.
     */
    #[On('two-factor-enabled')]
    public function onTwoFactorEnabled(): void
    {
        $this->twoFactorEnabled = true;
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">{{ __('Security settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Current password')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />
            <flux:input
                wire:model="password"
                :label="__('New password')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-password-button">
                    {{ __('Save') }}
                </flux:button>
            </div>
        </form>

        @if ($canManageTwoFactor)
            <section class="mt-12">
                <flux:heading>{{ __('Two-factor authentication') }}</flux:heading>
                <flux:subheading>{{ __('Manage your two-factor authentication settings') }}</flux:subheading>

                <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
                    @if ($twoFactorEnabled)
                        <div class="space-y-4">
                            <flux:text>
                                {{ __('You will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                            </flux:text>

                            <div class="flex justify-start">
                                <flux:button
                                    variant="danger"
                                    wire:click="disable"
                                >
                                    {{ __('Disable 2FA') }}
                                </flux:button>
                            </div>

                            <livewire:pages::settings.two-factor.recovery-codes :$requiresConfirmation />
                        </div>
                    @else
                        <div class="space-y-4">
                            <flux:text variant="subtle">
                                {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                            </flux:text>

                            <flux:modal.trigger name="two-factor-setup-modal">
                                <flux:button
                                    variant="primary"
                                    wire:click="$dispatch('start-two-factor-setup')"
                                >
                                    {{ __('Enable 2FA') }}
                                </flux:button>
                            </flux:modal.trigger>

                            <livewire:pages::settings.two-factor-setup-modal :requires-confirmation="$requiresConfirmation" />
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @if ($canManagePasskeys)
            <section class="mt-12">
                <flux:heading>{{ __('Passkeys') }}</flux:heading>
                <flux:subheading>{{ __('Manage your passkeys for passwordless sign-in') }}</flux:subheading>

                <div class="mt-6 flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
                    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        @forelse ($passkeys as $passkey)
                            <div class="flex items-center justify-between p-4 {{ ! $loop->last ? 'border-b border-zinc-200 dark:border-zinc-700' : '' }}">
                                <div class="flex items-center gap-4">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-800">
                                        <flux:icon.key class="size-5 text-zinc-500 dark:text-zinc-400" />
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2.5">
                                            <p class="font-medium tracking-tight">{{ $passkey['name'] }}</p>
                                            @if ($passkey['authenticator'])
                                                <flux:badge size="sm">{{ $passkey['authenticator'] }}</flux:badge>
                                            @endif
                                        </div>
                                        <p class="text-zinc-500 dark:text-zinc-400 text-xs">
                                            {{ __('Added :time', ['time' => $passkey['created_at_diff']]) }}
                                            @if ($passkey['last_used_at_diff'])
                                                <span class="opacity-50 mx-1">/</span>
                                                {{ __('Last used :time', ['time' => $passkey['last_used_at_diff']]) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    icon:variant="outline"
                                    wire:click="confirmDelete({{ $passkey['id'] }})"
                                    class="text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50"
                                />
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                                    <flux:icon.key class="size-7 text-zinc-400 dark:text-zinc-500" />
                                </div>
                                <p class="font-medium">{{ __('No passkeys yet') }}</p>
                                <flux:text class="mt-1">{{ __('Add a passkey to sign in without a password') }}</flux:text>
                            </div>
                        @endforelse
                    </div>

                    <x-passkey-registration />
                </div>
            </section>
        @endif
    </x-pages::settings.layout>

    <flux:modal
        name="delete-passkey-modal"
        class="max-w-md md:min-w-md"
        @close="closeDeleteModal"
        wire:model="showDeleteModal"
    >
        <div class="space-y-6">
            <div class="space-y-2">
                <flux:heading size="lg">{{ __('Remove passkey') }}</flux:heading>
                <flux:text>
                    {{ __('Are you sure you want to remove the passkey ":name"? You will no longer be able to use it to sign in.', ['name' => $deletingPasskeyName]) }}
                </flux:text>
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button
                    variant="outline"
                    wire:click="closeDeleteModal"
                >
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button
                    variant="danger"
                    wire:click="deletePasskey"
                >
                    {{ __('Remove passkey') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</section> --}}
