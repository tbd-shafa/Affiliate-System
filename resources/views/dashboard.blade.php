<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                <div class="p-6 text-gray-900">
                    @if (Auth::user()->roles->contains('name', 'admin'))
                        <h3>Welcome to Admin Panel</h3>
                        <p>Manage users and settings.</p>
                    @elseif (Auth::user()->roles->contains('name', 'user'))
                        <h3>Welcome to User Panel</h3>
                        <p>Explore subscription plans and become an affiliate.</p>
                    @elseif (Auth::user()->roles->contains('name', 'affiliate_user'))
                        <h3>Welcome to Affiliate Panel</h3>
                        <p>View your affiliate link and earnings.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
