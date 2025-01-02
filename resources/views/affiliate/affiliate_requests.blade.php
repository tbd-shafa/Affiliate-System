<x-app-layout>

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
                <div class="overflow-x-auto">
                    <div class="flex items-center justify-between " style="padding:10px;">
                        <h2 class="font-bold text-xl text-gray-800 leading-tight">
                            Affiliate Request List
                        </h2>

                    </div>
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Address</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bank Account Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bank Account No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bank Name</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Branch Address</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($pendingRequests as $request)
                                <tr class="border-t border-gray-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->address }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->acc_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->acc_no }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->bank_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->phone_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->branch_address }}
                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class=text-yellow-600 hover:text-yellow-900"
                                            data-modal-target="#approveModal{{ $request->id }}">
                                            Approve
                                        </button>


                                        {{--  <form method="POST" action="{{ route('affiliate.reject', $request->id) }}"
                                            style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class=text-red-600 hover:text-red-900 ml-4"
                                                onclick="return confirm('Are you sure you want to reject this Request?')">
                                                Reject
                                            </button>
                                        </form>  --}}

                                        <form method="POST" action="{{ route('affiliate.reject', $request->id) }}"
                                            style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4"
                                                id="rejectButton{{ $request->id }}"
                                                onclick="return disableButtonAndSubmit(event, '{{ $request->id }}')">
                                                Reject
                                            </button>
                                        </form>

                                        <script>
                                            function disableButtonAndSubmit(event, requestId) {
                                                // Prevent the default button action if necessary
                                                event.preventDefault();

                                                // Get the reject button by ID
                                                const button = document.getElementById(`rejectButton${requestId}`);

                                                // Disable the button to prevent multiple clicks
                                                button.disabled = true;

                                                // Change the button text to indicate processing
                                                button.innerText = 'Processing...';

                                                // Submit the form
                                                button.closest('form').submit();

                                                // Return false to prevent any further action (optional for redundancy)
                                                return false;
                                            }
                                        </script>


                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">No Request
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-4 py-4">
                    {{ $pendingRequests->links() }}
                </div>

                @foreach ($pendingRequests as $request)
                    <div id="approveModal{{ $request->id }}"
                        class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center {{ $errors->has('percentage') && old('request_id') == $request->id ? '' : 'hidden' }}">
                        <div class="bg-white w-1/3 rounded-lg shadow-lg">
                            <form method="POST" action="{{ route('affiliate.approve', $request->id) }}">
                                @csrf
                                <input type="hidden" name="request_id" value="{{ $request->id }}">
                                <div class="p-4 border-b flex justify-between items-center">
                                    <h5 class="text-lg font-semibold">Approve Request</h5>
                                    <!-- Close Button -->
                                    <button type="button" class="text-gray-500 hover:text-gray-700"
                                        onclick="closeModal('{{ $request->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-4">
                                    @if (session('error') && old('request_id') == $request->id)
                                        <div
                                            class="alert alert-danger text-red-600 p-2 mb-4 border border-red-300 rounded">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <p class="mb-4">Are you sure you want to approve this request set with Percentage?
                                    </p>
                                    <div>
                                        <label for="percentage{{ $request->id }}"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Set Percentage (Optional)
                                        </label>
                                        <input type="number" name="percentage" id="percentage{{ $request->id }}"
                                            class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('percentage') border-red-500 @enderror"
                                            min="0" max="100" value="10">
                                        @error('percentage')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="p-4 border-t flex justify-end gap-2">
                                    <input type="hidden" id="action_type{{ $request->id }}" name="action_type"
                                        value="">


                                    <!-- Approve Button -->
                                    {{-- <button type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                                        onclick="document.getElementById('action_type{{ $request->id }}').value = 'approve';">
                                        Approve
                                    </button> --}}

                                    <button type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                                        id="approveButton{{ $request->id }}"
                                        onclick="handleApprove('{{ $request->id }}')">
                                        Approve
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

                <script>
                    function handleApprove(requestId) {
                        // Get the approve button
                        const approveButton = document.getElementById(`approveButton${requestId}`);
                        const actionTypeInput = document.getElementById(`action_type${requestId}`);

                        // Set the action type
                        actionTypeInput.value = 'approve';

                        // Disable the button and indicate it's processing
                        approveButton.disabled = true;
                        approveButton.innerText = 'Processing...';

                        // Allow the form to submit
                        approveButton.closest('form').submit();
                    }

                    document.querySelectorAll('[data-modal-target]').forEach(button => {
                        button.addEventListener('click', () => {
                            const modalId = button.getAttribute('data-modal-target');
                            document.querySelector(modalId).classList.remove('hidden');
                        });
                    });

                    function closeModal(requestId) {
                        document.getElementById(`approveModal${requestId}`).classList.add('hidden');
                    }
                </script>
            </div>
        </div>
    </div>

</x-app-layout>
