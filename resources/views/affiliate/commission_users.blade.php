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
                <div class="flex items-center justify-between " style="padding:15px;">
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Commisions List
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>


                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Earned Commisions</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Payout Commisions</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Current Commisions</th>


                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($users as $user)
                                <tr class="border-t border-gray-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <a href="{{ route('admin.affiliate.earn.history', ['user' => $user->id]) }}"
                                            class="text-blue-500 hover:underline">
                                            ${{ number_format($user->commissions()->sum('earn_amount'), 2) }}
                                        </a>

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <a href="{{ route('admin.affiliate.payout.history', ['user' => $user->id]) }}"
                                            class="text-blue-500 hover:underline">
                                            ${{ number_format($user->payouts()->sum('amount'), 2) }}
                                        </a>


                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($user->commissions()->sum('earn_amount') - $user->payouts()->sum('amount'), 2) }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @php
                                            $availableBalance =
                                                $user->commissions()->sum('earn_amount') -
                                                $user->payouts()->sum('amount');
                                        @endphp


                                        <button
                                            class="@if ($availableBalance <= 0) text-gray-400 cursor-not-allowed @else text-yellow-600 hover:text-yellow-900 @endif"
                                            data-modal-target="#approveModal{{ $user->id }}"
                                            @if ($availableBalance <= 0) disabled @endif>
                                            Make Payout
                                        </button>
                                        <div id="approveModal{{ $user->id }}"
                                            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center 
                                            @if ($errors->any() && old('user_id') == $user->id) @else hidden @endif">
                                            <div class="bg-white w-1/3 rounded-lg shadow-lg">
                                                <form method="POST"
                                                    action="{{ route('commission.payout', $user->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <div class="p-4 border-b flex justify-between items-center">
                                                        <h5 class="text-lg font-semibold">Current Balance:
                                                            ${{ number_format($user->commissions()->sum('earn_amount') - $user->payouts()->sum('amount'), 2) }}
                                                        </h5>
                                                        <!-- Close Button -->
                                                        <button type="button" class="text-gray-500 hover:text-gray-700"
                                                            onclick="closeModal('{{ $user->id }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="p-4">


                                                        <div class="mb-3">
                                                            <label for="amount{{ $user->id }}"
                                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                                Payout Amount<span style="color:red;">*</span>
                                                            </label>
                                                            <input type="number" name="amount"
                                                                id="amount{{ $user->id }}"
                                                                class="block mb-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror"
                                                                min="0"
                                                                max="{{ number_format($user->commissions()->sum('earn_amount') - $user->payouts()->sum('amount'), 2) }}"
                                                                value="{{ old('amount') }}" required>
                                                            @error('amount')
                                                                <span
                                                                    class="text-red-600 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="remarks{{ $user->id }}"
                                                                class="block  text-sm font-medium text-gray-700 mb-2">
                                                                Remarks<span style="color:red;">*</span>
                                                            </label>
                                                            <input type="text" name="remarks"
                                                                id="remarks{{ $user->id }}"
                                                                class="block mb-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('remarks') border-red-500 @enderror"
                                                                value="{{ old('remarks') }}" required>
                                                            @error('remarks')
                                                                <span
                                                                    class="text-red-600 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="payment_by{{ $user->id }}"
                                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                                Payment By<span style="color:red;">*</span>
                                                            </label>
                                                            <input type="text" name="payment_by"
                                                                id="payment_by{{ $user->id }}"
                                                                class="block  mb-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('payment_by') border-red-500 @enderror"
                                                                value="{{ old('payment_by') }}" required>
                                                            @error('payment_by')
                                                                <span
                                                                    class="text-red-600 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="p-4 border-t flex justify-end gap-2">
                                                        <button type="submit"
                                                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                                                            id="PayoutButton{{ $user->id }}"
                                                            onclick="handlePayout('{{ $user->id }}')">
                                                            Submit
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <script>
                                            function handlePayout(userId) {
                                                // Get the approve button
                                                const PayoutButton = document.getElementById(`PayoutButton${userId}`);
                                                // Disable the button and indicate it's processing
                                                PayoutButton.disabled = true;
                                                PayoutButton.innerText = 'Processing...';

                                                // Allow the form to submit
                                                PayoutButton.closest('form').submit();
                                            }
                                            document.querySelectorAll('[data-modal-target]').forEach(button => {
                                                button.addEventListener('click', () => {
                                                    const modalId = button.getAttribute('data-modal-target');
                                                    const modal = document.querySelector(modalId);

                                                    // Reset the modal content
                                                    resetModalContent(modal);

                                                    // Show the modal
                                                    modal.classList.remove('hidden');
                                                });
                                            });

                                            function closeModal(userId) {
                                                const modal = document.getElementById(`approveModal${userId}`);
                                                modal.classList.add('hidden');
                                                resetModalContent(modal);
                                            }

                                            function resetModalContent(modal) {
                                                // Reset form inputs
                                                const form = modal.querySelector('form');
                                                if (form) {
                                                    form.reset(); // Resets all input fields
                                                }

                                                // Clear error messages
                                                modal.querySelectorAll('.text-red-600').forEach(errorMessage => {
                                                    errorMessage.textContent = ''; // Clear error text
                                                });

                                                // Remove error classes from input fields
                                                modal.querySelectorAll('.border-red-500').forEach(inputField => {
                                                    inputField.classList.remove('border-red-500');
                                                });
                                            }
                                        </script>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No users
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-4 py-4">
                    {{ $users->links() }}
                </div>




            </div>
        </div>
    </div>

</x-app-layout>
