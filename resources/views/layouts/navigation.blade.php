<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <style>
        .text-disabled {
            color: #9ca3af;
            /* A light gray color for disabled state */
            cursor: not-allowed;
            opacity: 0.6;
            /* Makes it visually distinct as disabled */
            pointer-events: none;
            /* Prevents interaction */
        }

        .max-w-7xl {
            max-width: 86rem !important;
        }
    </style>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Auth::user()->roles->contains('name', 'admin'))
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>Users</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('users.index', ['role' => 'admin'])">
                                {{ __('Admin Users') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('users.index', ['role' => 'affiliate_user'])">
                                {{ __('Affiliate Users') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('users.index', ['role' => 'user'])">
                                {{ __('Normal Users') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    <x-nav-link href="{{ route('affiliate.requests') }}" :active="request()->routeIs('affiliate.requests')">
                        {{ __('Affiliate Request') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('commission.percentage') }}" :active="request()->routeIs('commission.percentage')">
                        {{ __('Commission Percentage') }}
                    </x-nav-link>

                     <x-nav-link href="{{ route('affiliate.commission', ['role' => 'affiliate_user']) }}" :active="request()->routeIs('affiliate.commission')">
                        {{ __('Affiliate Commission') }}
                    </x-nav-link>

                @endif

                @if (Auth::user()->roles->contains('name', 'user') || Auth::user()->roles->contains('name', 'affiliate_user'))
                    <!-- Buy Subscription Plan button -->
                    <x-nav-link href="{{ route('subscriptions.index') }}" :active="request()->routeIs('subscription')">
                        {{ __('Buy Subscription Plan') }}
                    </x-nav-link>
                @endif

                @if (Auth::user()->roles->contains('name', 'user'))
                    <!-- Become an Affiliate button (for users only) -->
                    @if (!Auth::user()->affiliateUser || !in_array(Auth::user()->affiliateUser->status, ['pending', 'approved', 'Deleted']))
                        <x-nav-link href="{{ route('affiliate.create') }}" :active="request()->routeIs('affiliate.create')">
                            {{ __('Become Affiliate') }}
                        </x-nav-link>
                    @endif
                    @if (!Auth::user()->affiliateUser || in_array(Auth::user()->affiliateUser->status, ['pending']))
                        <!-- Disabled Become an Affiliate button for pending -->
                        <x-nav-link class="text-gray-500 cursor-not-allowed pointer-events-none text-disabled"
                            :active="request()->routeIs('affiliate.create')">
                            {{ __('Become Affiliate') }}
                        </x-nav-link>
                    @endif
                @endif

                @if (Auth::user()->roles->contains('name', 'affiliate_user') && !Auth::user()->affiliateUser)
                    <!-- This is to prevent the "Become an Affiliate" button from showing for affiliate users who are already affiliates -->
                    <x-nav-link href="{{ route('affiliate.create') }}" :active="request()->routeIs('affiliate.create')">
                        {{ __('Become Affiliate') }}
                    </x-nav-link>
                @endif

                @if (Auth::user()->roles->contains('name', 'affiliate_user'))
                    <!-- Affiliate-specific links -->
                    @if (Auth::user()->affiliateUser && Auth::user()->affiliateUser->status === 'approved')
                        

                        <x-nav-link href="{{ route('affiliate.link') }}" :active="request()->routeIs('affiliate.link')">
                        {{ __('Affiliate Panel') }}
                       </x-nav-link>

                      {{--  <x-nav-link href="{{ route('affiliate.commission.balance') }}" :active="request()->routeIs('affiliate-link')">
                            {{ __('Current Commission Balance') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('affiliate.referred.users') }}" :active="request()->routeIs('affiliate-link')">
                            {{ __('Referred Users') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('affiliate.earn.history') }}" :active="request()->routeIs('affiliate-link')">
                            {{ __('Earn History') }}
                        </x-nav-link>  --}}
                        
                    @endif
                @endif

            </div>


            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
