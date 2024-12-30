<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;


class RegisteredUserController extends Controller
{

    /**
     * Display the registration view.
     */
    public function create(Request $request)
    {
        // Your existing logic...
        return view('auth.register');
    }

    // public function create(Request $request): View
    // {
    //     $referrerCode = $request->query('referrer') ?: ''; // First check query, then cookie
    //     if (!empty($referrerCode)) {
    //         // Set the cookie for the referral code

    //         Cookie::queue('referrer_code', $referrerCode, 60 * 24);
    //     }

    //     return view('auth.register');
    // }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request): RedirectResponse
    // {

    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'user',
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     return redirect(route('dashboard', absolute: false));
    // }


    public function store(Request $request): RedirectResponse
    {

        // Validate input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activity_status' => 'active', // Default activity status
        ]);


        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Create user details entry
        $userDetails = UserDetail::create([
            'user_id' => $user->id,
            'address' => null,
            'acc_name' => null,
            'acc_no' => null,
            'bank_name' => null,
            'branch_address' => null,
            'phone_number' => null,
            'percentage_value' => null,
            'status' => 'just_created', // Default status
            'affiliate_code' => null, // Generate a unique affiliate code
        ]);


        $referrerCode = $request->query('referrer') ?: $request->cookie('referrer_code'); // First check query, then cookie

        // Save the referral code in a cookie if present
        if ($referrerCode) {
            // Set the cookie for the referral code

            $referrerDetails = UserDetail::where('affiliate_code', $referrerCode)
                ->where('status', 'approved')
                ->first();

            if ($referrerDetails) {
                // Insert into affiliate_referrals table
                DB::table('affiliate_referrals')->insert([
                    'referrer_id' => $referrerDetails->user_id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $cookie = cookie('referrer_code', '', -1); // Clear the cookie
            // Trigger registration events
            event(new Registered($user));
            Auth::login($user);

            // Redirect with cleared cookie
            return redirect(route('dashboard', absolute: false))->withCookie($cookie);
        } else {
            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        }
    }
}
