<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex items-center justify-between" style="padding-top:10px; padding-bottom:10px;">
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Welcome to Affiliate Panel
                </h2>
            </div>
            @php
                $roles = Auth::user()->roles->pluck('name')->toArray(); 
                $userDetails = Auth::user()->userDetail; 
            @endphp

            @if (in_array('affiliate_user', $roles))
                @if ($userDetails && $userDetails->status === 'approved')
                    @php
                        $affiliateLink = url('/register') . '?referrer=' . $userDetails->affiliate_code;
                    @endphp

                    <div class="flex items-center space-x-4">
                        <p>Your affiliate link: {{ $affiliateLink }} </p>
                        <button class="copy-link-btn px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            data-link="{{ $affiliateLink }}">
                            Copy Link
                        </button>
                    </div>
                    <p>Share your affiliate link for earnings.</p>
                @endif
            @endif
        </div>
    </div>
</div>
