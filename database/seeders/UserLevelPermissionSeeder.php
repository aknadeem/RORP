<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserLevel;
use Illuminate\Database\Seeder;

class UserLevelPermissionSeeder extends Seeder
{
    public function run()
    {
        $this->createUserLevels();

	    $this->createPermissions(); 
	    
	    $userlevel = UserLevel::first();
	    $userlevel->permissions()->sync(Permission::all());
    }

    protected function createUserLevels()
    {
    	if (UserLevel::count() == 0){
        $data = [
		    [
		       'title' => 'Super Admin',
		       'slug' => 'super-admin',
		    ],
		    [
		       'title' => 'Admin',
		       'slug' => 'admin',
		    ],
		    [
		       'title' => 'HOD',
		       'slug' => 'hod',
		    ],
		    [
		       'title' => 'Assistant Manager',
		       'slug' => 'assistant-manager',
		    ],
		    [
		       'title' => 'Supervisor',
		       'slug' => 'supervisor',
		    ],
		    [
		       'title' => 'Resident',
		       'slug' => 'resident',
		    ],
		    [
		       'title' => 'Tenant',
		       'slug' => 'tenant',
		    ],
		    [
		       'title' => 'Family Member',
		       'slug' => 'family member',
		    ],
		];

		UserLevel::insert($data);
		}	else {
			echo "*User Level* Table Already has Data\n";
		}
    }

    protected function createPermissions()
    {
    	if (Permission::count() == 0){
        $pdata = [
		    [
		       'title' => 'Create',
		       'slug' => 'create-user-management',
		       'module_id' => 2,
		       'code_name' => 'user-management',
		    ],
		    [
		       'title' => 'Update',
		       'slug' => 'update-user-management',
		       'module_id' => 2,
		       'code_name' => 'user-management',
		    ],
		    [
		       'title' => 'Delete',
		       'slug' => 'delete-user-management',
		       'module_id' => 2,
		       'code_name' => 'user-management',
		    ],
		    [
		       'title' => 'View',
		       'slug' => 'view-user-management',
		       'module_id' => 2,
		       'code_name' => 'user-management',
		    ],
		];

		Permission::insert($pdata);
		}	else {
			echo "*Permission* Table Already has Data\n";
		}
    }
}