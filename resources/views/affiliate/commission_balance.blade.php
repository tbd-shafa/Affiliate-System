@extends('affiliate.affiliate-panel')

@section('content')
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <div class="flex items-center justify-between " style="padding-top:10px; padding-bottom:5px;">
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                       Current Commission Balance
                    </h2>

                </div>
                 <p>Total Earned Commission: ${{ number_format($totalCommission, 2) }}</p>
                 <p>Current Commission: ${{ number_format($currentCommision, 2) }}</p>
            </div>
            
        </div>
    </div>
@endsection


