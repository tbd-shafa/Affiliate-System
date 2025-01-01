<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-2">
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
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">


                <div class="overflow-x-auto">

                    <div class="flex items-center justify-between " style="padding-top:10px; padding-bottom:10px;">
                        <h2 class="font-bold text-xl text-gray-800 leading-tight">
                            Create Subscription
                        </h2>

                    </div>
                    <form method="POST" action="{{ route('subscriptions.buy') }}">
                        @csrf

                        <!-- Subscription Plan Dropdown -->
                        {{--  <div>
                            <x-input-label for="plan_id" :value="__('Subscription Plan')" />
                            <select id="plan_id" name="plan_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled selected>Select a subscription plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }} - ${{ number_format($plan->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('plan_id')" class="mt-2" />
                        </div> --}}
                        <div>
                            <x-input-label for="plan_id" class="inline-flex items-center">
                                <span>{{ __('Subscription Plan') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <select id="plan_id" name="plan_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled {{ old('plan_id') ? '' : 'selected' }}>Select a
                                    subscription plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                        {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ${{ number_format($plan->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('plan_id')" class="mt-2" />
                        </div>

                        <!-- stripe_price -->
                        <div class="mt-4">
                            
                            <x-input-label for="stripe_price" class="inline-flex items-center">
                                <span>{{ __('Stripe Price') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <x-text-input id="stripe_price" class="block mt-1 w-full" type="text" name="stripe_price"
                                :value="old('stripe_price')" required />
                            <x-input-error :messages="$errors->get('stripe_price')" class="mt-2" />
                        </div>

                        <!-- Package Name -->
                        <div class="mt-4">
                           
                            <x-input-label for="stripe_id" class="inline-flex items-center">
                                <span>{{ __('Stripe Price Id') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk here -->
                            </x-input-label>
                            <x-text-input id="stripe_id" class="block mt-1 w-full" type="text" name="stripe_id"
                                :value="old('stripe_id')" required />
                            <x-input-error :messages="$errors->get('stripe_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 mb-4">
                            <button type="submit"
                                class="inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                Submit
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
