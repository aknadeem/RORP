<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Eloquent::unguard();
        // $path = '/home/nadeem/Projects/OfficeProjects/RoyalOrchard/society_serives_management/database/seeders/modules.sql';
        // DB::unprepared(file_get_contents($path));
        // $this->command->info('Modules Seeder');

        $this->call([
            
            CountryProvinceSeeder::class,
            SocietySectorSeeder::class,
	        ModuleSeeder::class,
            UserLevelPermissionSeeder::class,
	        UserSeeder::class,
            DepartmentSeeder::class,
            HandyServiceTypeSeeder::class,
            ServentTypeSeeder::class,
            AboutUsSeeder::class,
            
	    ]); 

        // /home/nadeem/Projects/OfficeProjects/RoyalOrchard/society_serives_management/database/seeders


        
    }
}
