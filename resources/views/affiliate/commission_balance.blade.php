<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            create Affiliate Request
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <h2>Current Commission Balance</h2>
                    <p>Total Earned Commission: ${{ number_format($totalCommission, 2) }}</p>

                </div>

            </div>
        </div>
    </div>


</x-app-layout>
