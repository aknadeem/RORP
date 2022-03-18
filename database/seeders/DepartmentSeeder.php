<?php

namespace Database\Seeders;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\SubDepartmentSupervisor;
use App\Models\SubDepartmentManager;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $this->createDepartment();

	    $this->createSubdepartment(); 

	    $this->createSubdepartmentSupervisor(); 
	    $this->createSubdepartmentManager(); 
	    
	    // $userlevel = UserLevel::first();
	    // $userlevel->permissions()->sync(Permission::all());
    }

    protected function createDepartment()
    {
    	if (Department::count() == 0){
        $datat = [
		    [
		       'name' => 'Electrical-Core',
		       'slug' => 'electrical-core',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 0,
		    ],
		    [
		       'name' => 'Maintenance & Services',
		       'slug' => 'maintenance-services',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 1,
		    ],
			[
		       'name' => 'Security & Surveillance',
		       'slug' => 'security-surveillance',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 1,
		    ],
		    [
		       'name' => 'Ambulance & Medics',
		       'slug' => 'ambulance-medics',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 1,

		    ],
		    [
		       'name' => 'Pick & Drop Services',
		       'slug' => 'pick-drop-services',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 1,
		    ],
		    [
		       'name' => 'Town Planning',
		       'slug' => 'town-planning',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 0,
		    ],
		    [
		       'name' => 'Horticulture',
		       'slug' => 'horticulture',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 0,
		    ],
		    [
		       'name' => 'Internet & Cable services',
		       'slug' => 'internet-cable-services',
		       'society_id' => 1,
		       'is_complaint' => 1,
		       'is_service' => 1,
		    ],
		    [
		       'name' => 'Account',
		       'slug' => 'account',
		       'society_id' => 1,
		       'is_complaint' => 0,
		       'is_service' => 0,
		    ], 
		];

		Department::insert($datat);
		}	else {
			echo "*Department* Table Already has Data\n";
		}
    }

    protected function createSubdepartment()
    {
    	if (SubDepartment::count() == 0){
        $pdata = [
		    [
		       'name' => 'Street Lights',
		       'slug' => 'street-lights',
		       'department_id' => 1,
		    ],[
		       'name' => 'Installations',
		       'slug' => 'installations',
		       'department_id' => 2,
		    ],
		];

		SubDepartment::insert($pdata);
		}	else {
			echo "*SubDepartment* Table Already has Data\n";
		}
    }

    protected function createSubdepartmentSupervisor()
    {
    	if (SubDepartmentSupervisor::count() == 0){
        $data = [
		    [
		       'sub_department_id' => 1,
		       'supervisor_id' => 5,
		    ],
		];

		SubDepartmentSupervisor::insert($data);
		}	else {
			echo "*SubDepartmentSupervisor* Table Already has Data\n";
		}
    }

    protected function createSubdepartmentManager()
    {
    	if (SubDepartmentManager::count() == 0){
        $data = [
		    [
		       'sub_department_id' => 1,
		       'manager_id' => 4,
		    ],
		];

		SubDepartmentManager::insert($data);
		}	else {
			echo "*SubDepartmentSupervisor* Table Already has Data\n";
		}
    }

}