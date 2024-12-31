
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex items-center justify-between" style="padding-top:10px; padding-bottom:10px;">
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Welcome to Affiliate Panel
                </h2>
            </div>
            @php
                $roles = Auth::user()->roles->pluck('name')->toArray(); // Get all roles of the user as an array
                $userDetails = Auth::user()->userDetail; // Assuming the relationship is defined
            @endphp

            @if (in_array('affiliate_user', $roles))
                @if ($userDetails && $userDetails->status === 'approved')
                    @php
                        // Generate the affiliate link
                        $affiliateLink = url('/register') . '?referrer=' . $userDetails->affiliate_code;
                    @endphp

                    <div class="flex items-center space-x-4">
                        <p>Your affiliate link: <a href="{{ $affiliateLink }}" target="_blank">{{ $affiliateLink }}</a></p>
                        <button id="copyLinkBtn" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            Copy Link
                        </button>
                    </div>
                    <p>Share your affiliate link for earnings.</p>
                @else
                    <p>Your affiliate request is not approved yet.</p>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const copyLinkBtn = document.getElementById('copyLinkBtn');

        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', () => {
                const affiliateLink = "{{ $affiliateLink }}";

                // Copy the link to the clipboard
                navigator.clipboard.writeText(affiliateLink).then(() => {
                    alert('Affiliate link copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy affiliate link: ', err);
                });
            });
        }
    });
</script>

    

