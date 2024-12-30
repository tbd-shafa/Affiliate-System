<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Commission Percentage
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

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

                <form action="{{ route('commission.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Method override -->

                    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}">

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">User Name</label>
                        <input type="text" id="name" value="{{ $user->name }}"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            disabled>
                    </div>

                    <div class="mb-4">
                        <label for="percentage" class="block text-sm font-medium text-gray-700">Commission
                            Percentage</label>
                        <input type="number" id="percentage" name="percentage" value="{{ $user->percentage_value }}"
                            min="0" max="100"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('percentage')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center bg-black text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                            Update Percentage
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
