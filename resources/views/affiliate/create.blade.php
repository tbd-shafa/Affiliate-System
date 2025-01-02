<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <div class="flex items-center justify-between " style="padding-top:10px; padding-bottom:10px;">
                        <h2 class="font-bold text-xl text-gray-800 leading-tight">
                            Create Affiliate Request
                        </h2>

                    </div>
                    <form method="POST" action="{{ route('affiliate.store') }}">
                        @csrf

                        <div class="mb-4">

                            <x-input-label for="address" class="inline-flex items-center">
                                <span>{{ __('Address') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <textarea id="address" name="address" class="block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('address') }}</textarea>
                             <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="mb-4">

                            <x-input-label for="acc_name" class="inline-flex items-center">
                                <span>{{ __('Bank Account
                                                                Name') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <input id="acc_name" name="acc_name" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('acc_name') }}"
                                required />
                                 <x-input-error :messages="$errors->get('acc_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">

                            <x-input-label for="acc_no" class="inline-flex items-center">
                                <span>{{ __('Bank Account
                                                                Number') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <input id="acc_no" name="acc_no" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('acc_no') }}"
                                required />
                                <x-input-error :messages="$errors->get('acc_no')" class="mt-2" />
                        </div>

                        <div class="mb-4">

                            <x-input-label for="bank_name" class="inline-flex items-center">
                                <span>{{ __('Bank Name') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <input id="bank_name" name="bank_name" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('bank_name') }}"
                                required />
                                 <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
                        </div>
                        <div class="mb-4">

                            <x-input-label for="phone_number" class="inline-flex items-center">
                                <span>{{ __('Phone Number') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <x-text-input id="phone_number" class="block mt-1 w-full p-2 border rounded" type="number"
                                name="phone_number" required />
                           
                             <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>
                        <div class="mb-4">


                            <x-input-label for="branch_address" class="inline-flex items-center">
                                <span>{{ __('Branch Address') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>

                            <input id="branch_address" name="branch_address" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('branch_address') }}" required />
                                <x-input-error :messages="$errors->get('branch_address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 mb-4">
                            <button type="submit"
                                class="inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                Submit Request
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
