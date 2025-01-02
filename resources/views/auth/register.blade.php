<x-guest-layout>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"
            x-data="{ show: true }" x-show="show">
            <button type="button" class="absolute top-0 right-0 mt-2 mr-2 text-red-700 hover:text-red-900"
                @click="show = false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 9.293l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414L10 8.586z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Hidden input for referrer -->
        
        <!-- Name -->
        <div>
            
            <x-input-label for="name" class="inline-flex items-center">
                                <span>{{ __('Name') }}</span>
                                <span class="text-red-500 ml-1">*</span> 
            </x-input-label>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            
            <x-input-label for="email" class="inline-flex items-center">
                                <span>{{ __('Email') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            
             <x-input-label for="password" class="inline-flex items-center">
                                <span>{{ __('Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            
             <x-input-label for="password_confirmation" class="inline-flex items-center">
                                <span>{{ __('Confirm Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
            </x-input-label>

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (!request()->query('referrer'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
          @endif

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
