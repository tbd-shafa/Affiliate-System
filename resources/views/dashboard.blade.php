<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (Auth::user()->role === 'admin')
                        <h3>Welcome to Admin Panel</h3>
                        <p>Manage users and settings.</p>
                    @elseif (Auth::user()->role === 'user')
                        <h3>Welcome to User Panel</h3>
                        <p>Explore subscription plans and become an affiliate.</p>
                    @elseif (Auth::user()->role === 'affiliate_user')
                        <h3>Welcome to Affiliate Panel</h3>
                        <p>View your affiliate link and earnings.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

