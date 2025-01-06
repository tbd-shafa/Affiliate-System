@extends('affiliate.affiliate-panel')

@section('content')
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
                            <p>Your affiliate link: <span id="affiliate-link">{{ $affiliateLink }}</span></p>
                            <button id="copy-link-btn"
                                class="copy-link-btn px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                Copy Link
                            </button>
                        </div>
                       
                         @if ($userDetails && $userDetails->affiliate_status === 'disable')
                         <p>Sorry! Your Affiliate Link Currently Disabled  Your LInk Won't Work</p>
                         @endif

                         @if ($userDetails && $userDetails->affiliate_status === 'enable')
                          <p>Share your affiliate link for earnings.</p>
                         @endif

                        <script>
                            document.getElementById('copy-link-btn').addEventListener('click', function(event) {
                                // Select the affiliate link text
                                const link = document.getElementById('affiliate-link').textContent;

                                // Create a temporary input element to copy the text
                                const input = document.createElement('input');
                                input.value = link;
                                document.body.appendChild(input);

                                // Select and copy the text
                                input.select();
                                document.execCommand('copy');

                                // Remove the input element
                                document.body.removeChild(input);

                                // Change button text to "Link Copied!"
                                const button = event.target;
                                const originalText = button.textContent;
                                button.textContent = 'Link Copied!';

                                // Revert back to original text after 2 seconds
                                setTimeout(() => {
                                    button.textContent = originalText;
                                }, 2000);
                            });
                        </script>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
