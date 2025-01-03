<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AffiliateUser;
use App\Models\AffiliateReferral;
use App\Models\UserDetail;
use App\Models\Commission;
use App\Models\Subscription;
use App\Models\Payout;
use App\Models\User;

class SubscriptionController extends Controller
{
    // Display subscription plans
    public function index()
    {
        // Fetch available subscription plans (assume a model SubscriptionPlan exists)
        $plans = \App\Models\SubscriptionPlan::where('status', 'active')->latest()->get();

        return view('subscriptions.index', compact('plans'));
    }



    public function buySubscription(Request $request)
    {
        // Validate the request data
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',  // Ensure the plan exists
            'stripe_price' => 'required',  // Stripe price is required
            'stripe_id' => 'required|unique:subscriptions,stripe_id',  // Stripe ID must be unique
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Save the subscription for the user
        $subscription = Subscription::create([
            'user_id' => $user->id,  // User's ID
            'subscription_plan_id' => $request->plan_id,  // Subscription plan ID
            'type' => 'default',  // Subscription type (default in this case)
            'stripe_id' => $request->stripe_id,  // Stripe ID for the subscription
            'stripe_status' => 'active',  // Set the status to active
            'stripe_price' => $request->stripe_price,  // The price of the subscription
            'quantity' => 1,  // Assuming it's always 1 for simplicity
            'trial_ends_at' => null,  // No trial period
            'ends_at' => now()->addMonth(),  // Set subscription to expire after 1 month
        ]);

        $affiliateReferral = AffiliateReferral::where('user_id', $user->id)->first();
        $referrerId = $affiliateReferral?->referrer_id;

        // Check if there is a referrer and calculate commission
        if ($referrerId) {
            // Get the plan price from the SubscriptionPlan model
            $planPrice = \App\Models\SubscriptionPlan::where('id', $request->plan_id)->value('price');

            // Get the commission percentage from the user_details table
            $commissionPercentage = \App\Models\UserDetail::where('user_id', $referrerId)
                ->value('percentage_value') ?? 10;  // Default to 10% if no setting found

            // Calculate the commission amount
            $commissionAmount = ($planPrice * $commissionPercentage) / 100;

            // Insert the commission data into the database, including the subscriptions_id
            Commission::create([
                'user_id' => $user->id,  // The user who made the purchase
                'affiliate_user_id' => $referrerId,  // The affiliate who referred the user
                'subscriptions_id' => $subscription->id,  // Use the subscription id
                'package_amount' =>  $planPrice,
                'earn_amount' => $commissionAmount,
                'package_id' => $request->plan_id,  // The subscription plan ID
                'percentage' => $commissionPercentage,  // The commission percentage
            ]);
        }

        // Redirect to the subscriptions index with a success message
        return redirect()->route('subscriptions.index')->with('success', 'Subscription purchased successfully.');
    }


    public function viewCommisionPercentage()
    {

        $data = User::join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('user_details.status', 'approved')
            ->select('users.id', 'users.name', 'user_details.percentage_value')
            ->paginate(10);

        return view('subscriptions.percentage', compact('data'));
    }
    public function editCommisionPercentage($id)
    {


        $user = User::join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $id)
            ->where('user_details.status', 'approved')
            ->select('users.id', 'users.name', 'user_details.percentage_value')
            ->firstOrFail();

        return view('subscriptions.percentage_edit', compact('user'));
    }



    public function updateCommisionPercentage(Request $request, $id)
    {

        $request->validate([

            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Find the user's details and update the percentage value
        $userDetails = UserDetail::where('user_id', $id)->first();

        if ($userDetails) {
            $userDetails->percentage_value = $request->percentage;
            $userDetails->save();
        } else {
            return redirect()->route('commission.percentage')->withErrors(['error' => 'User details not found.']);
        }

        return redirect()->route('commission.percentage')->with('success', 'Commission percentage updated successfully.');
    }

    public function viewAffiliateCommision($role)
    {
        $users = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })
            ->orderBy('created_at', 'desc') // Order by creation date, latest first
            ->paginate(10);

        return view('affiliate.commission_users', compact('users', 'role'));
    }

    public function commissionPayout(Request $request, $id)
    {
       
        $user = User::findOrFail($id);
        $currentBalance = $user->commissions()->sum('earn_amount') - $user->payouts()->sum('amount');
        
        // Validate input
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0', 'max:' . $currentBalance],
            'remarks' => ['required'],
            'payment_by' => ['required', 'string', 'max:255'],
        ]);
     
        // Process payout logic here
        Payout::create([
            'affiliate_user_id' => $id,
            'amount' => $validated['amount'],
            'payment_by' => $validated['payment_by'],
            'remarks' => $validated['remarks'],
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Payout processed successfully!');

       
    }
}
