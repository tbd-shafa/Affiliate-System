<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\AffiliateUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Dynamic index for any role
    public function index($role)
    {
        $users = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->paginate(10);

        return view('users.index', compact('users', 'role'));
    }

    // Dynamic create method for any role
    public function create($role)
    {
        return view('users.create', compact('role'));
    }

    // Dynamic store method for any role
    public function store(Request $request, $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role, // dynamic role
        ]);

        if ($role === 'affiliate_user') {
            $request->validate([
                'address' => 'required|string|max:255',
                'acc_name' => 'required|string|max:255',
                'acc_no' => 'required|string|max:20',
                'bank_name' => 'required|string|max:255',
                'branch_address' => 'required|string|max:255',
            ]);
            // Store additional data in the AffiliateUser table
            $referralCode = encrypt($user->id);
            $uniqueAffiliateLink = route('register') . '?referrer=' . $referralCode;
            AffiliateUser::create([
                'user_id' => $user->id, // Reference to the user ID
                'address' => $request->address,
                'acc_name' => $request->acc_name,
                'acc_no' => $request->acc_no,
                'bank_name' => $request->bank_name,
                'branch_address' => $request->branch_address,
                'status' => 'approved', // Default status
                'affiliate_link' => $uniqueAffiliateLink, // You can generate a dynamic link here if needed
            ]);
            Setting::updateOrCreate(
                ['key' => $user->id],
                ['value' => 10]
            );
        }

        return redirect()->route('users.index', ['role' => $role])->with('success', ucfirst($role) . ' User Created Successfully.');
    }

    // Dynamic edit method for any role

    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        $role = $user->role;
        $affiliateDetails = $role === 'affiliate_user'
            ? AffiliateUser::where('user_id', $id)->first()
            : null;

        return view('users.edit', compact('user', 'role', 'affiliateDetails'));
    }

    // Dynamic update method for any role

    public function update(Request $request, $id)
    {
      
        $user = User::find($id);
        $oldRole = $user->role;
        $newRole = $request->role;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:admin,affiliate_user,user', // Validate the role
        ]);

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role' => $request->role, // Use the new role
        ]);
        if ($oldRole === 'affiliate_user' && $newRole !== 'affiliate_user') {
            AffiliateUser::where('user_id', $user->id)->delete();
            Setting::where('key', $user->id)->delete();
        }
        // If the role is 'affiliate_user', handle affiliate details
        if ($newRole === 'affiliate_user') {
            $request->validate([
                'address' => 'required|string|max:255',
                'acc_name' => 'required|string|max:255',
                'acc_no' => 'required|string|max:20',
                'bank_name' => 'required|string|max:255',
                'branch_address' => 'required|string|max:255',
            ]);
            $referralCode = encrypt($user->id);
            $uniqueAffiliateLink = route('register') . '?referrer=' . $referralCode;

            AffiliateUser::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'address' => $request->address,
                    'acc_name' => $request->acc_name,
                    'acc_no' => $request->acc_no,
                    'bank_name' => $request->bank_name,
                    'branch_address' => $request->branch_address,
                    'status' => 'approved', // Default status
                    'affiliate_link' => $uniqueAffiliateLink, // You can generate a dynamic link here if needed
                ]
            );

            // Insert into settings table only if no matching record exists
            if (!Setting::where('key', $user->id)->exists()) {
                Setting::create([
                    'key' => $user->id,
                    'value' => 10, // Default value
                ]);
            }
        }


        return redirect()->route('users.edit', ['id' => $user->id])->with('success', ucfirst($request->role) . ' User Updated Successfully.');
    }

    // Dynamic destroy method for any role
    public function destroy($id)
    {
        $user = User::find($id);
        $role = $user->role;
        
         if ($role === 'affiliate_user') {
            if (Setting::where('key', $user->id)->exists()) {
                Setting::where('key', $user->id)->delete();
             }
         }
         
     
        $user->delete();

        return redirect()->route('users.index', ['role' => $role])->with('success', ucfirst($role) . ' User Has Been Deleted successfully.');
    }
}
