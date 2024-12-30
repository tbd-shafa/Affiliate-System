
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
               
                <div class="p-6 text-gray-900">
                    @php
                        $roles = Auth::user()->roles->pluck('name')->toArray(); // Get all roles of the user as an array
                        $userDetails = Auth::user()->userDetail; // Assuming the relationship is defined
                    @endphp

                    @if (in_array('admin', $roles))
                        <h3>Welcome to Admin Panel</h3>
                        <p>Manage users and settings.</p>
                    @elseif (in_array('affiliate_user', $roles))
                        <h3>Welcome to Affiliate Panel</h3>
                        @if ($userDetails && $userDetails->status === 'approved')
                            @php
                                // Generate the affiliate link
                                $affiliateLink = url('/register') . '?referrer=' . $userDetails->affiliate_code;
                            @endphp

                            <p>Your affiliate link: <a href="{{ $affiliateLink }}" target="_blank">{{ $affiliateLink }}</a></p>
                            <p>Share your affiliate link for earnings.</p>
                        @else
                            <p>Your affiliate request is not approved yet.</p>
                        @endif
                    @endif
                   
                </div>

            </div>
        </div>
    </div>

