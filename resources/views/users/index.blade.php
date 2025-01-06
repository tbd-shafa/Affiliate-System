<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">


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
                        @if ($role == 'user')
                            Normal User List
                        @elseif($role == 'affiliate_user')
                            Affiliate Users List
                        @elseif($role == 'admin')
                            Admin Users List
                        @endif
                    </h2>
                    <a href="{{ route('users.create', ['role' => strtolower($role)]) }}"
                        class="inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                        </svg>
                        Create
                        @if ($role == 'user')
                            Normal User
                        @elseif($role == 'affiliate_user')
                            Affiliate User
                        @elseif($role == 'admin')
                            Admin User
                        @endif
                    </a>
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

                                @if ($role == 'affiliate_user')
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Commisions Earned</th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Affiliate Status</th>
                                @endif

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
                                    @if ($role == 'affiliate_user')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($user->commissions()->sum('earn_amount'), 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-affiliate-status"
                                                    data-user-id="{{ $user->id }}"
                                                    {{ $user->userDetail->affiliate_status === 'enable' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">


                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="text-yellow-600 hover:text-yellow-900">Edit</a>


                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.toggle-affiliate-status').forEach(button => {
            button.addEventListener('change', function() {
                const userId = this.getAttribute('data-user-id');
                const newStatus = this.checked ? 'enable' : 'disable';

                Swal.fire({
                    title: `Are you sure you want to ${newStatus} affiliate status?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, do it!',
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch('{{ route('toggle.affiliate.status') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    user_id: userId,
                                    status: newStatus
                                }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Success!', data.message, 'success');
                                } else {
                                    Swal.fire('Error!', data.message, 'error');
                                    this.checked = !this
                                        .checked; // Revert the checkbox if error
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                                this.checked = !this.checked; // Revert the checkbox if error
                            });
                    } else {
                        this.checked = !this.checked; // Revert the checkbox if cancelled
                    }
                });
            });
        });
    </script>
</x-app-layout>
