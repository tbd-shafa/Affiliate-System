<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            create Users
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <form method="POST" action="{{ route('users.store', ['role' => strtolower($role)]) }}">
                        @csrf
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />

                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                         @if($role === 'affiliate_user')
                          <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea id="address" name="address" class="block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('address') }}</textarea>
                        </div>
                       
                        <div class="mb-4">
                            <label for="acc_name" class="block text-sm font-medium text-gray-700">Account Name</label>
                            <input id="acc_name" name="acc_name" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('acc_name') }}"
                                required />
                        </div>

                        <div class="mb-4">
                            <label for="acc_no" class="block text-sm font-medium text-gray-700">Account Number</label>
                            <input id="acc_no" name="acc_no" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('acc_no') }}"
                                required />
                        </div>

                        <div class="mb-4">
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input id="bank_name" name="bank_name" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('bank_name') }}"
                                required />
                        </div>

                        <div class="mb-4">
                            <label for="branch_address" class="block text-sm font-medium text-gray-700">Branch
                                Address</label>
                            <input id="branch_address" name="branch_address" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('branch_address') }}" required />
                        </div>
                        @endif
                        <div class="flex items-center justify-end mt-4 mb-4">
                            <button type="submit"
                                class="inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                Create {{ $role }} User
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

</x-app-layout>
