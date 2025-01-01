<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">
                    @php
                        $roles = Auth::user()->roles->pluck('name')->toArray(); // Get all roles of the user as an array
                        $userDetails = Auth::user()->userDetail; // Assuming the relationship is defined
                    @endphp

                    @if (in_array('admin', $roles))
                        <h3>Welcome to Admin Panel</h3>
                        <p>Manage Users and Other Settings.</p>
                    @elseif (in_array('affiliate_user', $roles))
                        <h3>Welcome to Affiliate Panel</h3>
                        @if ($userDetails && $userDetails->status === 'approved')
                        @else
                            <p>Your affiliate request is not approved yet.</p>
                        @endif
                    @elseif (in_array('user', $roles))
                        @if ($userDetails && $userDetails->status === 'pending')
                            <h3>Affiliate Request Pending</h3>
                            <p>Your affiliate request is currently under review. Please check back later.</p>
                        @else
                            <h3>Welcome to User Panel</h3>
                        @endif
                    @else
                        <h3>Welcome</h3>
                        <p>Your role is not recognized.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
