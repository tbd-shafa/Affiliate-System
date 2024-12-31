
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg grid grid-cols-12 gap-4">
                <!-- Left Menu -->
                <div class="col-span-12 md:col-span-3 p-6 text-gray-900">
                    <a href="{{ route('affiliate.link') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                        >
                        {{ __('Affiliate Link') }}
                    </a>
                    <a href="{{ route('affiliate.commission.balance') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                        >
                        {{ __('Current Commission Balance') }}
                    </a>
                    <a href="{{ route('affiliate.referred.users') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                       >
                        {{ __('Referred Users') }}
                    </a>
                    <a href="{{ route('affiliate.earn.history') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                        >
                        {{ __('Earn History') }}
                    </a>
                </div>

                <!-- Content Section -->
                <div class="col-span-12 md:col-span-9 bg-white p-6 rounded shadow" id="affiliate-content">
                
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



