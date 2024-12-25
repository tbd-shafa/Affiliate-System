<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Update Users
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <div class="py-12">
                        @if (session('success'))
                            <div x-data="{ open: true }" x-show="open" x-transition
                                class="alert alert-success mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex">

                                        <span>{{ session('success') }}</span>
                                    </div>
                                    <button @click="open = false" class="ml-4 text-gray-500 hover:text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="overflow-x-auto">
                                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <!-- Name -->
                                        <div>
                                            <x-input-label for="name" :value="__('Name')" />
                                            <x-text-input id="name" class="block mt-1 w-full" type="text"
                                                name="name" :value="old('name', $user->name)" required autofocus
                                                autocomplete="name" />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        <!-- Email Address -->
                                        <div class="mt-4">
                                            <x-input-label for="email" :value="__('Email')" />
                                            <x-text-input id="email" class="block mt-1 w-full" type="email"
                                                name="email" :value="old('email', $user->email)" required autocomplete="username" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <!-- Role -->
                                        <!-- Role -->
                                        <div class="mt-4">
                                            <x-input-label for="role" :value="__('Role')" />
                                            <select id="role" name="role"
                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                                                <option value="admin"
                                                    {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="affiliate_user"
                                                    {{ old('role', $user->role) == 'affiliate_user' ? 'selected' : '' }}>
                                                    Affiliate User</option>
                                                <option value="user"
                                                    {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Normal
                                                    User</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                        </div>

                                        <!-- Affiliate User Fields -->
                                        <div id="affiliate-fields" style="display: none;">
                                            <div class="mb-4">
                                                <label for="address"
                                                    class="block text-sm font-medium text-gray-700">Address</label>
                                                <textarea id="address" name="address" class="block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('address', $affiliateDetails->address ?? '') }}</textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label for="acc_name"
                                                    class="block text-sm font-medium text-gray-700">Account Name</label>
                                                <input id="acc_name" name="acc_name" type="text"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm"
                                                    value="{{ old('acc_name', $affiliateDetails->acc_name ?? '') }}"
                                                    required />
                                            </div>

                                            <div class="mb-4">
                                                <label for="acc_no"
                                                    class="block text-sm font-medium text-gray-700">Account
                                                    Number</label>
                                                <input id="acc_no" name="acc_no" type="text"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm"
                                                    value="{{ old('acc_no', $affiliateDetails->acc_no ?? '') }}"
                                                    required />
                                            </div>

                                            <div class="mb-4">
                                                <label for="bank_name"
                                                    class="block text-sm font-medium text-gray-700">Bank Name</label>
                                                <input id="bank_name" name="bank_name" type="text"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm"
                                                    value="{{ old('bank_name', $affiliateDetails->bank_name ?? '') }}"
                                                    required />
                                            </div>

                                            <div class="mb-4">
                                                <label for="branch_address"
                                                    class="block text-sm font-medium text-gray-700">Branch
                                                    Address</label>
                                                <input id="branch_address" name="branch_address" type="text"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm"
                                                    value="{{ old('branch_address', $affiliateDetails->branch_address ?? '') }}"
                                                    required />
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const roleSelect = document.getElementById('role');
                                                const affiliateFields = document.getElementById('affiliate-fields');

                                                // Function to toggle the visibility of affiliate fields
                                                function toggleAffiliateFields() {
                                                    if (roleSelect.value === 'affiliate_user') {
                                                        affiliateFields.style.display = 'block';
                                                    } else {
                                                        affiliateFields.style.display = 'none';
                                                    }
                                                }

                                                // Initial check on page load
                                                toggleAffiliateFields();

                                                // Add event listener to role select dropdown
                                                roleSelect.addEventListener('change', toggleAffiliateFields);
                                            });
                                        </script>


                                        <!-- Password -->
                                        <div class="mt-4">
                                            <x-input-label for="password" :value="__('Password')" />
                                            <x-text-input id="password" class="block mt-1 w-full" type="password"
                                                name="password" autocomplete="new-password" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mt-4">
                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                type="password" name="password_confirmation"
                                                autocomplete="new-password" />
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>



                                        <div class="flex items-center justify-end mt-4 mb-4">

                                            <a href="{{ route('users.index', ['role' => strtolower($role)]) }}"
                                                class="inline-flex items-center bg-gray-300 text-black hover:bg-gray-400 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2">
                                                Back to
                                                @if ($role == 'affiliate_user')
                                                    Affiliate User List
                                                @elseif($role == 'admin')
                                                    Admin User List
                                                @else
                                                    Normal Users List
                                                @endif
                                            </a>

                                            <button type="submit"
                                                class="ml-4 inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                                Update
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>

</x-app-layout>
