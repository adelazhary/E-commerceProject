<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirect(session('url.intended', RouteServiceProvider::HOME), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Don\'t have an account?') }}</p>
        <form action="{{ route('register') }}">
            <x-primary-button class="mt-2 mx-auto block">
                {{ __('Register Here') }}
            </x-primary-button>
        </form>
    </div>

    <div class="flex items-center space-x-4">
        <a href="{{ route('socialite.redirect', 'github') }}"
            class="flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 shadow-md px-4 py-2">
            <svg class="w-6 h-6 fill-current text-gray-700" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M12 2C6.13 2 2 6.13 2 12s4.13 10 10 10 10-4.13 10-10-4.13-10-10-10zm-2.25 7c.7-1.3 2.25-2.5 3.5-2.5s2.75 1.2 3.5 2.5 1.25 2.75 2.5 3.5c-.7 1.3-2.25 2.5-3.5 2.5s-2.75-1.2-3.5-2.5-1.25-2.75-2.5-3.5z"
                    clip-rule="evenodd" />
            </svg>
            <span class="ml-2 text-sm font-medium text-gray-700">Continue with GitHub</span>
        </a>

        <a href="{{ route('socialite.redirect', 'google') }}">
            <x-secondary-button>
                <img src="https://tuk-cdn.s3.amazonaws.com/can-uploader/sign_in-svg2.svg" alt="Google Login">
                <span>Continue with Google</span>
            </x-secondary-button>
        </a>
    </div>
</div>
</div>
