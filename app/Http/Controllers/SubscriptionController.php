<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AffiliateUser;
use App\Models\AffiliateReferral;
use App\Models\Setting;
use App\Models\Commission;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionController extends Controller
{
    // Display subscription plans
    public function index()
    {
        // Fetch available subscription plans (assume a model SubscriptionPlan exists)
        $plans = \App\Models\SubscriptionPlan::where('status', 'active')->get();

        return view('subscriptions.index', compact('plans'));
    }



    // public function buySubscription(Request $request)
    // {
    //     // Validate the request data
    //     $request->validate([
    //         'plan_id' => 'required|exists:subscription_plans,id',  // Ensure the plan exists
    //         'stripe_price' => 'required',  // Stripe price is required
    //         'stripe_id' => 'required|unique:subscriptions,stripe_id',  // Stripe ID must be unique
    //     ]);

    //     // Get the authenticated user
    //     $user = Auth::user();
    //     // Check if there is an affiliate referral for the user
        

    //     // Save the subscription for the user
    //     $subscription = Subscription::create([
    //         'user_id' => $user->id,  // User's ID
    //         'subscription_plan_id' => $request->plan_id,  // Subscription plan ID
    //         'type' => 'default',  // Subscription type (default in this case)
    //         'stripe_id' => $request->stripe_id,  // Stripe ID for the subscription
    //         'stripe_status' => 'active',  // Set the status to active
    //         'stripe_price' => $request->stripe_price,  // The price of the subscription
    //         'quantity' => 1,  // Assuming it's always 1 for simplicity
    //         'trial_ends_at' => null,  // No trial period
    //         'ends_at' => now()->addMonth(),  // Set subscription to expire after 1 month
    //     ]);

    //     $affiliateReferral = AffiliateReferral::where('user_id', $user->id)->first();
    //     $referrerId = $affiliateReferral?->referrer_id;
       
    //     // Check if there is a referrer and calculate commission
    //     if ($referrerId) {
    //         // Get the plan price from the SubscriptionPlan model
    //         $planPrice = \App\Models\SubscriptionPlan::where('id', $request->plan_id)->value('price');
    //         // Get the commission percentage from the settings table
    //         //$commissionPercentage = ::where('', $referrerId)->value('percentage_value') ?? 10;  // Default to 10% if no setting found
    //         $commissionPercentage = \App\Models\UserDetail::where('user_id', $referrerId)
    //         ->value('percentage_value') ?? 10;
          
    //         // Calculate the commission amount
    //         $commissionAmount = ($planPrice * $commissionPercentage) / 100;
           
          
    //         // Insert the commission data into the database
    //         Commission::create([
    //             'user_id' => $user->id,  // The user who made the purchase
    //             'affiliate_user_id' => $referrerId,  // The affiliate who referred the user
    //             'subscriptions_id' => $subscription->id,
    //             'package_amount' =>  $planPrice,  
    //             'earn_amount' => $commissionAmount,  
    //             'package_id' => $request->plan_id,  // The subscription plan ID
    //             'percentage' => $commissionPercentage,  // The commission percentage
    //         ]);
    //     }

    //     // Redirect to the subscriptions index with a success message
    //     return redirect()->route('subscriptions.index')->with('success', 'Subscription purchased successfully.');
    // }

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
        // Fetch users with their percentage from the `settings` table
        $data = Setting::leftJoin('users', 'settings.key', '=', 'users.id') // Join settings with users
        ->select('settings.key', 'users.name', 'settings.value as percentage') // Select relevant fields
        ->paginate(10);

        return view('subscriptions.percentage', compact('data'));
    }
    public function editCommisionPercentage($id)
    {
        $user = Setting::leftJoin('users', 'settings.key', '=', 'users.id')
        ->select('settings.key as user_id', 'users.name', 'settings.value as percentage')
        ->where('settings.key', $id)
        ->firstOrFail();

    return view('subscriptions.percentage_edit', compact('user'));

    }
    
    public function updateCommisionPercentage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Update or create a new record in the settings table
        Setting::updateOrCreate(
            ['key' => $request->user_id],
            ['value' => $request->percentage]
        );

        return redirect()->route('commission.percentage')->with('success', 'Commission percentage updated successfully.');
    }

}
