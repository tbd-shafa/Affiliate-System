<x-app-layout>
 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

             
                <div class="overflow-x-auto">
                    <div class="flex items-center justify-between " style="padding-top:10px; padding-bottom:10px;">
                        <h2 class="font-bold text-xl text-gray-800 leading-tight">
                            Edit Commission Percentage
                        </h2>

                    </div>
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
                            <input type="number" id="percentage" name="percentage"
                                value="{{ $user->percentage_value }}" min="0" max="100"
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
    </div>
</x-app-layout>
