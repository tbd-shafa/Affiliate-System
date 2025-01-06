<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
         
            <x-input-label for="email" class="inline-flex items-center">
                                <span>{{ __('Email') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
             </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
           
             <x-input-label for="password" class="inline-flex items-center">
                                <span>{{ __('Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            
             <x-input-label for="password_confirmation" class="inline-flex items-center">
                                <span>{{ __('Confirm Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
            </x-input-label>

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
