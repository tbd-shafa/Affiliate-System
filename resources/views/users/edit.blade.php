<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="overflow-x-auto">

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
                            <div class="flex items-center justify-between "
                                style="padding-top:10px; padding-bottom:10px;">
                                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                                    Edit Users
                                </h2>

                            </div>
                            <div class="overflow-x-auto">
                                <form method="POST" action="{{ route('users.update', $user->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Name -->
                                    <div class="mb-4">
                                        <x-input-label for="name" :value="__('Name')" />
                                        <x-text-input id="name" class="block mt-1 w-full p-2 border rounded"
                                            type="text" name="name" :value="old('name', $user->name)" required autofocus
                                            autocomplete="name" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <!-- Email Address -->
                                    <div class="mb-4">
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" class="block mt-1 w-full p-2 border rounded"
                                            type="email" name="email" :value="old('email', $user->email)" required
                                            autocomplete="username" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <!-- Roles -->
                                    <div class="mb-4">
                                        <x-input-label :value="__('Assign Roles')" />
                                        <div>
                                            @foreach ($roles as $roleItem)
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="roles[]" value="{{ $roleItem->name }}"
                                                        class="role-checkbox"
                                                        {{ in_array($roleItem->name, $userRoles) ? 'checked' : '' }}>
                                                    <span class="ml-2">{{ ucfirst($roleItem->name) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <x-input-label for="password" :value="__('Password')" />
                                        <x-text-input id="password" class="block mt-1 w-full p-2 border rounded"
                                            type="password" name="password" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-4">
                                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                        <x-text-input id="password_confirmation"
                                            class="block mt-1 w-full p-2 border rounded" type="password"
                                            name="password_confirmation" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>

                                    <div id="affiliate-fields"
                                        style="{{ in_array('affiliate_user', $userRoles) ? 'display: block;' : 'display: none;' }}">

                                        <!-- Address -->
                                        <div class="mb-4">
                                            <x-input-label for="address" :value="__('Address')" />
                                            <x-text-input id="address" class="block mt-1 w-full p-2 border rounded"
                                                type="text" name="address" :value="old('address', $userDetails->address ?? '')" required />
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>

                                        <!-- Bank Account Name -->
                                        <div class="mb-4">
                                            <x-input-label for="acc_name" :value="__('Bank Account Name')" />
                                            <x-text-input id="acc_name" class="block mt-1 w-full p-2 border rounded"
                                                type="text" name="acc_name" :value="old('acc_name', $userDetails->acc_name ?? '')" required />
                                            <x-input-error :messages="$errors->get('acc_name')" class="mt-2" />
                                        </div>

                                        <!-- Bank Account Number -->
                                        <div class="mb-4">
                                            <x-input-label for="acc_no" :value="__('Bank Account Number')" />
                                            <x-text-input id="acc_no" class="block mt-1 w-full p-2 border rounded"
                                                type="number" name="acc_no" :value="old('acc_no', $userDetails->acc_no ?? '')" required />
                                            <x-input-error :messages="$errors->get('acc_no')" class="mt-2" />
                                        </div>

                                        <!-- Bank Name -->
                                        <div class="mb-4">
                                            <x-input-label for="bank_name" :value="__('Bank Name')" />
                                            <x-text-input id="bank_name" class="block mt-1 w-full p-2 border rounded"
                                                type="text" name="bank_name" :value="old('bank_name', $userDetails->bank_name ?? '')" required />
                                            <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
                                        </div>

                                        <!-- Branch Address -->
                                        <div class="mb-4">
                                            <x-input-label for="branch_address" :value="__('Branch Address')" />
                                            <x-text-input id="branch_address"
                                                class="block mt-1 w-full p-2 border rounded" type="text"
                                                name="branch_address" :value="old('branch_address', $userDetails->branch_address ?? '')" required />
                                            <x-input-error :messages="$errors->get('branch_address')" class="mt-2" />
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="mb-4">
                                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                                            <x-text-input id="phone_number"
                                                class="block mt-1 w-full p-2 border rounded" type="number"
                                                name="phone_number" :value="old('phone_number', $userDetails->phone_number ?? '')" required />
                                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                        </div>

                                        <!-- Commision Percentage Value -->
                                        <div class="mb-4">
                                            <x-input-label for="percentage_value" :value="__('Commision Percentage Value')" />
                                            <x-text-input id="percentage_value"
                                                class="block mt-1 w-full p-2 border rounded" type="number"
                                                name="percentage_value" :value="old(
                                                    'percentage_value',
                                                    $userDetails->percentage_value ?? 10,
                                                )" required />
                                            <x-input-error :messages="$errors->get('percentage_value')" class="mt-2" />
                                        </div>

                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const affiliateFields = document.getElementById('affiliate-fields');
                                            const roleCheckboxes = document.querySelectorAll('.role-checkbox');
                                            const affiliateInputs = affiliateFields.querySelectorAll('input');

                                            function toggleAffiliateFields() {
                                                const isAffiliateChecked = Array.from(roleCheckboxes).some(
                                                    checkbox => checkbox.checked && checkbox.value === 'affiliate_user'
                                                );

                                                if (isAffiliateChecked) {
                                                    affiliateFields.style.display = 'block';
                                                    affiliateInputs.forEach(input => {
                                                        input.setAttribute('required', 'required');
                                                    });
                                                } else {
                                                    affiliateFields.style.display = 'none';
                                                    affiliateInputs.forEach(input => {
                                                        input.removeAttribute('required');
                                                    });
                                                }
                                            }

                                            roleCheckboxes.forEach(checkbox => {
                                                checkbox.addEventListener('change', toggleAffiliateFields);
                                            });

                                            // Initialize on page load
                                            toggleAffiliateFields();
                                        });
                                    </script>

                                    <div class="flex items-center justify-end  py-4">
                                        <button type="submit"
                                            class="inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                            Update User
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

</x-app-layout>
