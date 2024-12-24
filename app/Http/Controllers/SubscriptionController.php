<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AffiliateUser;
use App\Models\AffiliateReferral;
use App\Models\Setting;
use App\Models\Commission;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    // Display subscription plans
    public function index()
    {
        // Fetch available subscription plans (assume a model SubscriptionPlan exists)
        $plans = \App\Models\SubscriptionPlan::where('status', 'active')->get();

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
        // Check if there is an affiliate referral for the user
        $affiliateReferral = AffiliateReferral::where('user_id', $user->id)->first();
        // If a referral exists, get the referrer's ID
        $referrerId = $affiliateReferral?->referrer_id;

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

        // Check if there is a referrer and calculate commission
        if ($referrerId) {
            // Get the plan price from the SubscriptionPlan model
            $planPrice = \App\Models\SubscriptionPlan::where('id', $request->plan_id)->value('price');
            // Get the commission percentage from the settings table
            $commissionPercentage = Setting::where('key', $referrerId)->value('value') ?? 10;  // Default to 10% if no setting found
            // Calculate the commission amount
            $commissionAmount = ($planPrice * $commissionPercentage) / 100;
            // Find the affiliate user based on the referrer's ID
            $affiliateUser = AffiliateUser::find($referrerId);
            // Insert the commission data into the database
            Commission::create([
                'user_id' => $user->id,  // The user who made the purchase
                'affiliate_user_id' => $referrerId,  // The affiliate who referred the user
                'amount' => $commissionAmount,  // The commission amount calculated
                'package_id' => $request->plan_id,  // The subscription plan ID
                'percentage' => $commissionPercentage,  // The commission percentage
            ]);
        }

        // Redirect to the subscriptions index with a success message
        return redirect()->route('subscriptions.index')->with('success', 'Subscription purchased successfully.');
    }
}
