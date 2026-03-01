<x-guest-layout>
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl overflow-hidden rounded-xl border border-gray-200">

        <div class="flex flex-col items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Diskominfo" class="w-21 h-21 mb-3 transform hover:scale-105 transition duration-300 ease-in-out">
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="w-full space-y-6">
            @csrf
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
                <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
                <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button class="w-full justify-center px-6 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition duration-300 ease-in-out">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
            <br>
        </form>
    </div>
</x-guest-layout>