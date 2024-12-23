<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Dynamic index for any role
    public function index($role)
    {
        $users = User::where('role', $role)->paginate(10);
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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role, // dynamic role
        ]);

        return redirect()->route('users.index', ['role' => $role])->with('success', ucfirst($role) . ' User Created Successfully.');
    }

    // Dynamic edit method for any role

    public function edit($id)
    {
        
        $user = User::find($id);
       
        $role = $user->role;
        return view('users.edit', compact('user', 'role'));
        
    }

    // Dynamic update method for any role
  
    public function update(Request $request, $id)
    {
      
        $user = User::find($id);
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

        return redirect()->route('users.edit', ['id' => $user->id])->with('success', ucfirst($request->role) . ' User Updated Successfully.');
    }

    // Dynamic destroy method for any role
    public function destroy($id)
    {
        $user = User::find($id);
        $role = $user->role;
        $user->delete();

        return redirect()->route('users.index', ['role' => $role])->with('success', ucfirst($role) . ' User Has Been Deleted successfully.');
    }
}

