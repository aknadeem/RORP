<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AboutUs::count() == 0){
        $data = [
		    [
		       'title' => 'Pakistan',
		       'description' => 'Description Detail',
		    ],
		];

		AboutUs::insert($data);
		} else {
			echo "*AboutUs* Table Already has Data\n";
		}
    }
}
