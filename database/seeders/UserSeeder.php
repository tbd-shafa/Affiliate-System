<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;



class UserSeeder extends Seeder
{
    public function run()
    {
        // Insert data into the 'roles' table
        $roleId = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         DB::table('roles')->insertGetId([
            'name' => 'affiliate_user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
         DB::table('roles')->insertGetId([
            'name' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert data into the 'users' table
        $user = DB::table('users')->insertGetId([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'activity_status' => 'active',
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert data into the 'user_details' table with all fields set to null
        DB::table('user_details')->insert([
            'user_id' => $user, // Link to the newly created user
            'address' => null,
            'acc_name' => null,
            'acc_no' => null,
            'bank_name' => null,
            'branch_address' => null,
            'phone_number' => null,
            'percentage_value' => null,
            'status' => 'just_created',
            'affiliate_code' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert data into the 'role_user' table
        DB::table('role_user')->insert([
            'user_id' => $user,  // Link to the user created
            'role_id' => $roleId, // Role ID (admin role)
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}