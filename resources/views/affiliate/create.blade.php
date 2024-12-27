<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            create Affiliate Request
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <form method="POST" action="{{ route('affiliate.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea id="address" name="address" class="block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="acc_name" class="block text-sm font-medium text-gray-700">Bank Account
                                Name</label>
                            <input id="acc_name" name="acc_name" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('acc_name') }}"
                                required />
                        </div>

                        <div class="mb-4">
                            <label for="acc_no" class="block text-sm font-medium text-gray-700">Bank Account
                                Number</label>
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
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full p-2 border rounded" type="number"
                                name="phone_number" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <label for="branch_address" class="block text-sm font-medium text-gray-700">Branch
                                Address</label>
                            <input id="branch_address" name="branch_address" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('branch_address') }}" required />
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
