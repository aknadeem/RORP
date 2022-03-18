<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\SubModule;
use Illuminate\Database\Capsule\Eloquent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        // $this->createModules();
      // Eloquent::unguard();

      $path = '/home/nadeem/Projects/OfficeProjects/RoyalOrchard/society_serives_management/database/seeders/modules.sql';
      $sql = file_get_contents($path);
      DB::statement($sql);
      $this->command->info('Modules Seeder From SQL File');
      
      $this->createSubModules();
    }

    protected function createModules()
    {
        if (Module::count() == 0){
        $data = [
            [
              'title' => 'Society Management',
              'slug' => 'society-management',
            ],
            [
              'title' => 'User Management',
              'slug' => 'user-management',
            ],
        ];

        Module::insert($data);
        }   else {
            echo "*Module* Table Already has Data\n";
        }
    }

    protected function createSubModules()
    {
        if (SubModule::count() == 0){
        $data = [
            [
               'title' => 'Societyblocks',
               'slug' => 'societyblocks',
               'module_id' => 1,
            ],
            [
               'title' => 'Permission',
               'slug' => 'permission',
               'module_id' => 2,
            ],
            [
               'title' => 'UserLevel',
               'slug' => 'userlevel',
               'module_id' => 2,
            ],
            [
               'title' => 'Users',
               'slug' => 'users',
               'module_id' => 2,
            ],
        ];

        SubModule::insert($data);
        }   else {
            echo "*SubModule* Table Already has Data\n";
        }
    }
}
