<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg grid grid-cols-12 gap-4">
                <!-- Left Menu -->
                <div class="col-span-12 md:col-span-3 p-6 text-gray-900">
                    <div style="width:70%;">
                        <x-nav-link href="{{ route('affiliate.link') }}" :active="request()->routeIs('affiliate.link')">
                            {{ __('Affiliate link') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('affiliate.commission.balance') }}" :active="request()->routeIs('affiliate.commission.balance')">
                            {{ __('Current Commission') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('affiliate.referred.users') }}" :active="request()->routeIs('affiliate.referred.users')">
                            {{ __('Referred Users') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('affiliate.earn.history') }}" :active="request()->routeIs('affiliate.earn.history')">
                            {{ __('Earn History') }}
                        </x-nav-link>
                         <x-nav-link href="{{ route('affiliate.payout.history') }}" :active="request()->routeIs('affiliate.payout.history')">
                            {{ __('Payout History') }}
                        </x-nav-link>
                    </div>


                </div>

                <!-- Content Section -->
                <div class="col-span-12 md:col-span-9 bg-white p-6 rounded shadow" id="affiliate-content">

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
