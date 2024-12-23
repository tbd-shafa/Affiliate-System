<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AffiliateUser;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class AffiliateController extends Controller
{
    public function create()
    {
        return view('affiliate.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'acc_name' => 'required|string|max:255',
            'acc_no' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
        ]);

        AffiliateUser::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'acc_name' => $request->acc_name,
            'acc_no' => $request->acc_no,
            'bank_name' => $request->bank_name,
            'branch_address' => $request->branch_address,
            'status' => 'pending',
            'affiliate_link' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Affiliate request submitted successfully!');
    }
    public function showPendingRequests()
    {
        $pendingRequests = AffiliateUser::where('status', 'pending')->get();
        return view('admin.affiliate_requests', compact('pendingRequests'));
    }

    public function approveRequest(Request $request, $id)
    {
        
        if ($request->approve === null) {
           
            $request->validate([
                'percentage' => 'required|numeric|min:0|max:100',
            ]);
        

        }
      
    
        $affiliateUser = AffiliateUser::findOrFail($id);
    
        // Generate a unique registration link with the affiliate's user_id
        $uniqueAffiliateLink = route('register') . '?referrer=' . $affiliateUser->user_id;
    
        // Update the affiliate user status and link
        $affiliateUser->update([
            'status' => 'approved',
            'affiliate_link' => $uniqueAffiliateLink,
        ]);
    
        // Save percentage in the settings table if provided
        if ($request->has('percentage') && $request->percentage !== null) {
            $request->validate([
                'percentage' => 'required|numeric|min:0|max:100',
            ]);
            Setting::updateOrCreate(
                ['key' => $affiliateUser->user_id],
                ['value' => $request->percentage]
            );
        }

        return redirect()->route('affiliate.requests')->with('success', 'Affiliate approved successfully!');
    }

    public function rejectRequest($id)
    {
        $affiliateUser = AffiliateUser::findOrFail($id);

        // Update status to rejected
        $affiliateUser->update(['status' => 'rejected']);

        return redirect()->route('affiliate.requests')->with('success', 'Affiliate rejected successfully!');
    }
}
