<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Affiliate Request List
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

                <table class="w-full border-collapse border border-gray-200 bg-white shadow-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">User</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Address</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Bank Account Name</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Bank Account No</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Bank Name</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Branch Address</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($pendingRequests as $request)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2">{{ $request->id }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $request->user->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $request->address }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $request->acc_name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $request->acc_no }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $request->bank_name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $request->branch_address }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $request->status }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <!-- Approve Button -->
                                    <button
                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition"
                                        data-modal-target="#approveModal{{ $request->id }}">
                                        Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <form method="POST" action="{{ route('affiliate.reject', $request->id) }}"
                                        class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition"
                                            onclick="return confirm('Are you sure you want to reject this request?')">
                                            Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="border px-4 py-2 text-center">No data found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $pendingRequests->links() }}
                </div>
                <!-- Approve Modal -->
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
                                            step="0.1" min="0" max="100"
                                            value="{{ old('percentage') }}">
                                        @error('percentage')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="p-4 border-t flex justify-end gap-2">
                                    <input type="hidden" id="action_type{{ $request->id }}" name="action_type"
                                        value="">

                                    <!-- Submit without Percentage -->
                                    <button type="submit"
                                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition"
                                        onclick="document.getElementById('action_type{{ $request->id }}').value = 'submit_without_percentage';">
                                        Submit with Default Percentage
                                    </button>
                                    <!-- Approve Button -->
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                                        onclick="document.getElementById('action_type{{ $request->id }}').value = 'approve';">
                                        Approve
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

                <script>
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
