<x-layouts::base :title="__('Login')">

    <main class="auth-page">

        <div class="auth-card">

            <!-- En-tête -->
            <div class="register-header">
                <h2>Connexion</h2>
                <p>
                    Connectez-vous à votre compte AKATSUKI.
                </p>
            </div>

            <!-- Message de session -->
            <x-auth-session-status
                class="text-center"
                :status="session('status')"
            />

            <!-- Formulaire -->
            <form
                method="POST"
                action="{{ route('login.store') }}"
                id="loginForm"
            >
                @csrf

                <!-- Email -->
                <div class="form-group">

                    <label for="email">
                        Adresse email
                    </label>

                    <div class="input-box">
                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="exemple@email.com"
                            required
                            autofocus
                            autocomplete="email"
                        >
                    </div>

                    @error('email')
                        <span class="form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                <!-- Mot de passe -->
                <div class="form-group">

                    <label for="password">
                        Mot de passe
                    </label>

                    <div class="input-box">
                        <i class="bi bi-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Votre mot de passe"
                            required
                            autocomplete="current-password"
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
                        <span class="form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                <!-- Se souvenir de moi -->
                <div class="login-options">

                    <label class="remember-me">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >

                        <span>
                            Se souvenir de moi
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            wire:navigate
                        >
                            Mot de passe oublié ?
                        </a>
                    @endif

                </div>


                <!-- Erreurs générales -->
                @if ($errors->any())
                    <div
                        id="loginMessage"
                        class="register-message"
                    >
                        <strong>
                            Impossible de vous connecter.
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
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                    Se connecter
                </button>

            </form>


            <!-- Inscription -->
            <div class="register-footer">

                <p>
                    Vous n'avez pas encore de compte ?

                    <a
                        href="{{ route('register') }}"
                        wire:navigate
                    >
                        S'inscrire
                    </a>
                </p>

            </div>

        </div>

    </main>

</x-layouts::base>


{{-- <x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    </div>
</x-layouts::auth> --}}
