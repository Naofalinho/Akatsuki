<x-layouts::base :title="__('Register')">

    <section class="register-section">
        <div class="container">

            <div class="register-card">

                <!-- En-tête -->
                <div class="register-header">
                    <h1>Créer un compte</h1>
                    <p>Rejoignez AKATSUKI et profitez de nos services.</p>
                </div>

                <!-- Message de session -->
                <x-auth-session-status
                    class="text-center"
                    :status="session('status')"
                />

                <!-- Formulaire -->
                <form
                    method="POST"
                    action="{{ route('register.store') }}"
                    id="registerForm"
                >
                    @csrf

                    <!-- Nom -->
                    <div class="form-group">

                        <label for="name">Nom</label>

                        <div class="input-box">
                            <i class="bi bi-person"></i>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Votre nom"
                                required
                                autofocus
                                autocomplete="name"
                            >
                        </div>

                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror

                    </div>


                    <!-- Email -->
                    <div class="form-group">

                        <label for="email">Adresse email</label>

                        <div class="input-box">
                            <i class="bi bi-envelope"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="exemple@email.com"
                                required
                                autocomplete="email"
                            >
                        </div>

                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror

                    </div>


                    <!-- Sexe -->
                    <div class="form-group">

                        <label for="sexe">Sexe</label>

                        <div class="input-box">
                            <i class="bi bi-gender-ambiguous"></i>

                            <select
                                id="sexe"
                                name="sexe"
                                required
                            >
                                <option value="">
                                    Sélectionnez votre sexe
                                </option>

                                <option
                                    value="homme"
                                    {{ old('sexe') === 'homme' ? 'selected' : '' }}
                                >
                                    Homme
                                </option>

                                <option
                                    value="femme"
                                    {{ old('sexe') === 'femme' ? 'selected' : '' }}
                                >
                                    Femme
                                </option>
                            </select>
                        </div>

                        @error('sexe')
                            <span class="form-error">{{ $message }}</span>
                        @enderror

                    </div>


                    <!-- Téléphone -->
                    <div class="form-group">

                        <label for="phone">Numéro de téléphone</label>

                        <div class="input-box">
                            <i class="bi bi-telephone"></i>

                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="+229 XX XX XX XX"
                                required
                                autocomplete="tel"
                            >
                        </div>

                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror

                    </div>


                    <!-- Mot de passe -->
                    <div class="form-group">

                        <label for="password">Mot de passe</label>

                        <div class="input-box">
                            <i class="bi bi-lock"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Votre mot de passe"
                                required
                                autocomplete="new-password"
                                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                data-target="password"
                                aria-label="Afficher le mot de passe"
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror

                    </div>


                    <!-- Confirmation du mot de passe -->
                    <div class="form-group">

                        <label for="password_confirmation">
                            Confirmer le mot de passe
                        </label>

                        <div class="input-box">
                            <i class="bi bi-lock-fill"></i>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Confirmez votre mot de passe"
                                required
                                autocomplete="new-password"
                                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                data-target="password_confirmation"
                                aria-label="Afficher le mot de passe"
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        @error('password_confirmation')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <!-- Conditions -->
                    <div class="register-terms">

                        <label>
                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                required
                            >

                            <span>
                                J'accepte les conditions d'utilisation.
                            </span>
                        </label>

                    </div>


                    <!-- Erreurs générales -->
                    @if ($errors->any())
                        <div id="registerMessage" class="register-message">

                            <strong>
                                Veuillez corriger les erreurs suivantes :
                            </strong>

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif


                    <!-- Bouton -->
                    <button
                        type="submit"
                        class="register-btn"
                        data-test="register-user-button"
                    >
                        Créer mon compte
                    </button>

                </form>


                <!-- Footer -->
                <div class="register-footer">

                    <p>
                        Vous avez déjà un compte ?

                        <a
                            href="{{ route('login') }}"
                            wire:navigate
                        >
                            Se connecter
                        </a>
                    </p>

                </div>

            </div>

        </div>
    </section>

</x-layouts::base>



{{-- <x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth> --}}
