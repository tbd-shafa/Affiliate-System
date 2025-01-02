<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Role;
// use App\Models\AffiliateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Dynamic index for any role
    public function index($role)
    {

        $users = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })
        ->orderBy('created_at', 'desc') // Order by creation date, latest first
        ->paginate(10);

        return view('users.index', compact('users', 'role'));
    }

    // Dynamic create method for any role
    public function create($role)
    {
        $roles = Role::all(); // Assuming you have a Role model
        return view('users.create', compact('role', 'roles'));
    }


    public function store(Request $request, $role)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required|array|min:1',
            'password' => 'required|string|min:8|confirmed',
            
            'address' => 'nullable|string',
            'acc_name' => 'nullable|string',
            'acc_no' => 'nullable|string|max:34',
            'bank_name' => 'nullable|string',
            'branch_address' => 'nullable|string',
            //'phone_number' => 'nullable|string',
            'phone_number' => [
                'required',
                'string',
                'regex:/^(\+?[1-9]\d{1,14}|0\d{10})$/',
                'min:11',
                'max:14', // E.164 format (max length 15)
            ],
            'percentage_value' => 'nullable|numeric|min:0|max:100',
        ]);

        // Step 1: Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'activity_status' => 'active',
        ]);

        // Step 2: Assign roles to the user
        // $roles = Role::whereIn('name', $validatedData['roles'])->get();
        // $user->roles()->sync($roles);

        $roles = Role::whereIn('name', $validatedData['roles'])->get();
        $pivotData = [];
        foreach ($roles as $role) {
            $pivotData[$role->id] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $user->roles()->attach($pivotData);


        // Step 3: Prepare data for user details
        $userDetailsData = [
            'user_id' => $user->id,
            'address' => null,
            'acc_name' => null,
            'acc_no' => null,
            'bank_name' => null,
            'branch_address' => null,
            'phone_number' => null,
            'percentage_value' => null,
            'status' => 'just_created',
            'affiliate_code' => null,
        ];

        if (in_array('affiliate_user', $validatedData['roles'])) {
            $affiliateCode = strtoupper(Str::random(10));
            // Ensure the affiliate code is unique
            while (UserDetail::where('affiliate_code', $affiliateCode)->exists()) {
               
                $affiliateCode = strtoupper(Str::random(10));
            }

            $userDetailsData['address'] = $validatedData['address'] ?? null;
            $userDetailsData['acc_name'] = $validatedData['acc_name'] ?? null;
            $userDetailsData['acc_no'] = $validatedData['acc_no'] ?? null;
            $userDetailsData['bank_name'] = $validatedData['bank_name'] ?? null;
            $userDetailsData['branch_address'] = $validatedData['branch_address'] ?? null;
            $userDetailsData['phone_number'] = $validatedData['phone_number'] ?? null;
            $userDetailsData['percentage_value'] = $validatedData['percentage_value'] ?? null;
            $userDetailsData['status'] = 'approved';
            $userDetailsData['affiliate_code'] = $affiliateCode;
        }

        // Step 4: Insert user details
        UserDetail::create($userDetailsData);



        return redirect()->route('users.index', ['role' => $role->name])
            ->with('success', "User  created successfully!");
    }

    
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        // Get the available roles
        $roles = Role::all();

        // Get the current roles of the user
        $userRoles = $user->roles->pluck('name')->toArray();

        // Get user details
        $userDetails = UserDetail::where('user_id', $user->id)->first();

        return view('users.edit', compact('user', 'roles', 'userRoles', 'userDetails'));
    }


    // Dynamic update method for any role

    public function update(Request $request, $id)
    {
        
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        // Validate data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'address' => 'nullable|string',
            'acc_name' => 'nullable|string',
            'acc_no' => 'nullable|string|max:34',
            'bank_name' => 'nullable|string',
            'branch_address' => 'nullable|string',
            //'phone_number' => 'nullable|string',
            'phone_number' => [
                'nullable',
                'string',
                'regex:/^(\+?[1-9]\d{1,14}|0\d{10})$/',
                'min:11',
                'max:14', // E.164 format (max length 15)
            ],
            'percentage_value' => 'nullable|numeric|min:0|max:100',
        ]);
      
        // Update user
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? bcrypt($validatedData['password']) : $user->password,
        ]);

        // Update roles
        $roles = Role::whereIn('name', $validatedData['roles'])->get();
        $pivotData = [];
        foreach ($roles as $role) {
            $pivotData[$role->id] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $user->roles()->sync($pivotData);

        // Determine if user has 'affiliate_user' role
        $hasAffiliateRole = $roles->contains('name', 'affiliate_user');

        // Update user details based on roles
        $userDetailsData = [
            'address' => $validatedData['address'] ?? null,
            'acc_name' => $validatedData['acc_name'] ?? null,
            'acc_no' => $validatedData['acc_no'] ?? null,
            'bank_name' => $validatedData['bank_name'] ?? null,
            'branch_address' => $validatedData['branch_address'] ?? null,
            'phone_number' => $validatedData['phone_number'] ?? null,
            'percentage_value' => $validatedData['percentage_value'] ?? null,
        ];

        if ($hasAffiliateRole) {
            $affiliateCode = strtoupper(Str::random(10));
            // Ensure the affiliate code is unique
            while (UserDetail::where('affiliate_code', $affiliateCode)->exists()) {
               
                $affiliateCode = strtoupper(Str::random(10));
            }
            // If 'affiliate_user' role is selected
            $userDetailsData['status'] = 'approved';
            $userDetailsData['affiliate_code'] = $affiliateCode;
        } else {
            // If 'affiliate_user' role is not selected
            $userDetailsData['status'] = 'just_created';
            $userDetailsData['affiliate_code'] = null;
        }

        UserDetail::updateOrCreate(['user_id' => $user->id], $userDetailsData);

        return redirect()->route('users.edit', ['id' => $user->id])->with('success', 'User Updated Successfully.');
    }



    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        $role = $user->roles->pluck('name')->first(); // Assuming user has roles assigned
        $user->activity_status = 'inactive'; // Set user status to inactive
        $user->save();

        // Soft delete the user
        $user->delete();

        // Update related user_details status to 'Deleted'
        if ($user->details) {
            $user->details->status = 'Deleted';
            $user->details->save();
        }
        return redirect()->back()
        ->with('success', ' User Has Been Deleted successfully.');
       
    }
}
