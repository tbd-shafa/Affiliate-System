<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AffiliateReferral;
use App\Models\AffiliateUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
       
         if ($request->referrer !== null) {
            $encryptedReferrerCode = $request->referrer;
        
            try {
                // Decrypt the referrer code to get the referrer ID
                $referrerId = decrypt($encryptedReferrerCode);
            } catch (\Exception $e) {
                // Handle the case where decryption fails and redirect back with an error
              
                return redirect()->back()->withErrors('Invalid or tampered referrer code.');
            }
        
            // Check if the referrer exists and is approved
            $referrerUser = AffiliateUser::where('user_id', $referrerId)->first();
        
            if (!$referrerUser || $referrerUser->status !== 'approved') {
                // Redirect back with an error if referrer is invalid or unapproved
               
                return redirect()->back()->withErrors('Invalid or unapproved referrer.');
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);
            
            if ($referrerId) {
                // Link the new user to the referring affiliate
                AffiliateReferral::create([
                    'referrer_id' => $referrerId,
                    'user_id' => $user->id,
                ]);
            }
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);
        }
        

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
