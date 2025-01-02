<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            create Users
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <form method="POST" action="{{ route('users.store', ['role' => strtolower($role)]) }}">
                        @csrf
                        <!-- Name -->
                        <div class="mb-4">

                            <x-input-label for="name" class="inline-flex items-center">
                                <span>{{ __('Name') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <x-text-input id="name" class="block mt-1 w-full p-2 border rounded" type="text"
                                name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">

                            <x-input-label for="email" class="inline-flex items-center">
                                <span>{{ __('Email') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <x-text-input id="email" class="block mt-1 w-full p-2 border rounded" type="email"
                                name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <!-- Roles -->
                        {{-- <div class="mb-4">
                            <x-input-label :value="__('Assign Roles')" />
                            
                            <div>
                                @foreach ($roles as $roleItem)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $roleItem->name }}"
                                            class="role-checkbox" {{ $roleItem->name === $role ? 'checked' : '' }}>
                                        <span class="ml-2">{{ ucfirst($roleItem->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div> --}}
                        <div class="mb-4">

                            <x-input-label class="inline-flex items-center">
                                <span>{{ __('Assign Roles') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <div>
                                @foreach ($roles as $roleItem)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $roleItem->name }}"
                                            class="role-checkbox"
                                            @if ($errors->has('roles')) {{-- No checkboxes should be checked if there is a role error --}}
                    @elseif(is_array(old('roles')) && in_array($roleItem->name, old('roles')))
                        checked {{-- Check the roles previously submitted --}}
                    @elseif($roleItem->name === $role && !old('roles'))
                        checked {{-- Initially check the role sent from the create function --}} @endif>
                                        <span class="ml-2">{{ ucfirst($roleItem->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            {{-- Display error messages for roles --}}
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                        </div>




                        <!-- Password -->
                        <div class="mb-4">

                            <x-input-label for="password" class="inline-flex items-center">
                                <span>{{ __('Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <x-text-input id="password" class="block mt-1 w-full p-2 border rounded" type="password"
                                name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">

                            <x-input-label for="password_confirmation" class="inline-flex items-center">
                                <span>{{ __('Confirm Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <x-text-input id="password_confirmation" class="block mt-1 w-full p-2 border rounded"
                                type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div id="affiliate-fields" style="display: none;">

                            <div class="mb-4">

                                <x-input-label for="address" class="inline-flex items-center">
                                    <span>{{ __('Address') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                                </x-input-label>
                                <x-text-input id="address" class="block mt-1 w-full p-2 border rounded" type="text"
                                    name="address" :value="old('address')" required />


                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>


                            <div class="mb-4">

                                <x-input-label for="acc_name" class="inline-flex items-center">
                                    <span>{{ __('Bank Account Name') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                                </x-input-label>
                                <x-text-input id="acc_name" class="block mt-1 w-full p-2 border rounded" type="text"
                                    name="acc_name" :value="old('acc_name')" required />

                                <x-input-error :messages="$errors->get('acc_name')" class="mt-2" />
                            </div>

                            <div class="mb-4">

                                <x-input-label for="acc_no" class="inline-flex items-center">
                                    <span>{{ __('Bank Account Number') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                                </x-input-label>
                                <x-text-input id="acc_no" class="block mt-1 w-full p-2 border rounded" type="number"
                                    name="acc_no" :value="old('acc_no')" required />
                                <x-input-error :messages="$errors->get('acc_no')" class="mt-2" />
                            </div>


                            <div class="mb-4">

                                <x-input-label for="bank_name" class="inline-flex items-center">
                                    <span>{{ __('Bank Name') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                                </x-input-label>
                                <x-text-input id="bank_name" class="block mt-1 w-full p-2 border rounded" type="text"
                                    name="bank_name" :value="old('bank_name')" required />
                                <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
                            </div>
                            <div class="mb-4">

                                <x-input-label for="branch_address" class="inline-flex items-center">
                                    <span>{{ __('Branch Address') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                                </x-input-label>
                                <x-text-input id="branch_address" class="block mt-1 w-full p-2 border rounded"
                                    type="text" name="branch_address" :value="old('branch_address')" required autofocus />
                                <x-input-error :messages="$errors->get('branch_address')" class="mt-2" />
                            </div>
                            <div class="mb-4">

                                <x-input-label for="phone_number" class="inline-flex items-center">
                                    <span>{{ __('Phone Number') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                                </x-input-label>
                                <x-text-input id="phone_number" class="block mt-1 w-full p-2 border rounded"
                                    type="number" name="phone_number" required />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>

                            <div class="mb-4">

                                <x-input-label for="percentage_value" class="inline-flex items-center">
                                    <span>{{ __('ommision Percentage') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                                </x-input-label>
                                <x-text-input id="percentage_value" class="block mt-1 w-full p-2 border rounded"
                                    type="number" name="percentage_value" :value="10" required />
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

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
