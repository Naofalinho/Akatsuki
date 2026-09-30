<x-layouts::base :title="__('Confirmer le mot de passe')">

    <div class="ak-confirm-page">

        <div class="ak-confirm-card">

            {{-- LOGO --}}
            <div class="ak-confirm-logo">
                AKAT<span>SUKI</span>
            </div>

            {{-- ICÔNE --}}
            <div class="ak-confirm-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            {{-- TITRE --}}
            <div class="ak-confirm-header">

                <h1>
                    Confirmer votre identité
                </h1>

                <p>
                    Cette section est protégée.
                    Veuillez confirmer votre mot de passe
                    avant de continuer.
                </p>

            </div>

            {{-- MESSAGE SESSION --}}
            <x-auth-session-status
                class="ak-confirm-status"
                :status="session('status')"
            />

            {{-- PASSKEY --}}
            <x-passkey-verify
                options-route="passkey.confirm-options"
                submit-route="passkey.confirm"
                :label="__('Confirmer avec une passkey')"
                :loading-label="__('Confirmation...')"
                :separator="__('Ou confirmer avec votre mot de passe')"
            />

            {{-- FORMULAIRE --}}
            <form
                method="POST"
                action="{{ route('password.confirm.store') }}"
                class="ak-confirm-form"
            >

                @csrf

                <div class="ak-confirm-field">

                    <label for="password">
                        Mot de passe
                    </label>

                    <div class="ak-confirm-input">

                        <i class="bi bi-lock-fill"></i>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="Entrez votre mot de passe"
                        >

                    </div>

                    @error('password')
                        <span class="ak-confirm-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <button
                    type="submit"
                    class="ak-confirm-button"
                    data-test="confirm-password-button"
                >
                    <i class="bi bi-shield-check"></i>

                    <span>
                        Confirmer et continuer
                    </span>
                </button>

            </form>

            {{-- RETOUR --}}
            <a
                href="{{ route('profile.edit') }}"
                class="ak-confirm-back"
            >
                <i class="bi bi-arrow-left"></i>

                Retour à mon profil
            </a>

        </div>

    </div>

</x-layouts::base>



{{-- <x-layouts::auth :title="__('Confirm password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify
            options-route="passkey.confirm-options"
            submit-route="passkey.confirm"
            :label="__('Confirm with passkey')"
            :loading-label="__('Confirming...')"
            :separator="__('Or confirm with password')"
        />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Confirm') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth> --}}
