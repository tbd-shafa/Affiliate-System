

   <x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between " style="padding:15px;">
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                      Payout History Of  {{ $user->name }}
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payout At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payout Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Remarks</th>
                         

                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($payoutHistory as $history)
                            <tr class="border-t border-gray-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $history->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($history->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $history->remarks }}
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No History
                                    
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <div class="mt-4 px-4 py-4">
                   {{ $payoutHistory->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

