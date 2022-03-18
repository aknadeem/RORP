<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run()
    {
		$user = [
            [
                'society_id' => 1,
                'society_sector_id' => 1,
                'user_level_id' => 1,
                'unique_id' => 'ROYL-superadmin1',
                'user_type' => "admin",
                'name' => "Super Admin",
                'email' => "admin@admin.com",
                'password' => Hash::make(123456),
                'is_active' => 1,
                'addedby' => 1 
            ],
            [
                'society_id' => 1,
                'society_sector_id' => 1,
                'user_level_id' => 2,
                'unique_id' => 'ROYL-superadmin22',
                'user_type' => "admin",
                'name' => "Admin",
                'email' => "admin2@admin.com",
                'password' => Hash::make(123456),
                'is_active' => 1,
                'addedby' => 1 
            ],
            [
                'society_id' => 1,
                'society_sector_id' => 1,
                'user_level_id' =>3,
                'unique_id' => 'ROYL-superadmin32',
                'user_type' => "admin",
                'name' => "Don Name hod",
                'email' => "admin3@admin.com",
                'password' => Hash::make(123456),
                'is_active' => 1,
                'addedby' => 1 
            ],
            [
                'society_id' => 1,
                'society_sector_id' => 1,
                'user_level_id' => 4,
                'unique_id' => 'ROYL-superadmin44',
                'user_type' => "admin",
                'name' => "Asst Manager",
                'email' => "admin4@admin.com",
                'password' => Hash::make(123456),
                'is_active' => 1,
                'addedby' => 1 
            ],
            [
                'society_id' => 1,
                'society_sector_id' => 1,
                'user_level_id' => 5,
                'unique_id' => 'ROYL-superadmin55',
                'user_type' => "admin",
                'name' => "Supervisor User",
                'email' => "admin51@admin.com",
                'password' => Hash::make(123456),
                'is_active' => 1,
                'addedby' => 1 
            ],
            
        ];


        User::insert($user);

        $users = User::get();


        foreach ($users as $user) {
            $userlevel = UserLevel::with('permissions')->find($user->user_level_id);
            if($userlevel->permissions){
                foreach ($userlevel->permissions as $per) {

                    $user->permissions()->attach($per->id);
                }
            }
        }

        // $userlevel = UserLevel::first();

        // $user->permissions()->sync(Permission::all());
    }
}
