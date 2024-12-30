<x-app-layout>

 <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg grid grid-cols-12 gap-4">
                <!-- Left Menu -->
                <div class="col-span-12 md:col-span-3 p-6 text-gray-900">
                    <a href="{{ route('affiliate.link') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                        data-url="{{ route('affiliate.link') }}">
                        {{ __('Affiliate Link') }}
                    </a>
                    <a href="{{ route('affiliate.commission.balance') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                        data-url="{{ route('affiliate.commission.balance') }}">
                        {{ __('Current Commission Balance') }}
                    </a>
                    <a href="{{ route('affiliate.referred.users') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                        data-url="{{ route('affiliate.referred.users') }}">
                        {{ __('Referred Users') }}
                    </a>
                    <a href="{{ route('affiliate.earn.history') }}"
                        class="block px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded hover:bg-gray-200 load-content"
                        data-url="{{ route('affiliate.earn.history') }}">
                        {{ __('Earn History') }}
                    </a>
                </div>

                <!-- Content Section -->
                <div class="col-span-12 md:col-span-9 bg-white p-6 rounded shadow" id="affiliate-content">
                    <!-- Default content can go here -->
                    <h3>Welcome to the Affiliate Panel</h3>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const contentArea = document.getElementById('affiliate-content');

    document.querySelectorAll('.load-content').forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();

            const url = link.getAttribute('data-url');

            // Fetch content via AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Inform Laravel it's an AJAX request
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch content');
                    }
                    return response.text();
                })
                .then(html => {
                    // Replace content in the content area
                    contentArea.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading content:', error);
                    contentArea.innerHTML = '<p>Error loading content.</p>';
                });
        });
    });
});

</script>
</x-app-layout>
