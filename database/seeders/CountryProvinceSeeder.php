<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Province;
use App\Models\City;

use Illuminate\Database\Seeder;

class CountryProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  		$this->createCountries();
	    $this->createProvinces();
	    $this->createCities();
    }

    protected function createCountries()
    {
    	if (Country::count() == 0){
	        $data = [
			    [
			       'name' => 'Pakistan',
			       'slug' => 'pakistan',
			    ],
			];

			Country::insert($data);
		} else {
			echo "*Country* Table Already has Data\n";
		}
    }

    protected function createProvinces()
    {
    	if (Province::count() == 0){
        $data = [
		    [
		       'name' => 'Sindh',
		       'slug' => 'sindh',
		       'country_id' => 1,
		    ],
		    [
		       'name' => 'Punjab',
		       'slug' => 'punjab',
		       'country_id' => 1,
		    ],
		    [
		       'name' => 'Khyber Pakhtunkhwa',
		       'slug' => 'khyber-pakhtunkhwa',
		       'country_id' => 1,
		    ],
		    [
		       'name' => 'Islamabad',
		       'slug' => 'islamabad',
		       'country_id' => 1,
		    ],
		    [
		       'name' => 'Balochistan',
		       'slug' => 'balochistan',
		       'country_id' => 1,
		    ],
		    [
		       'name' => 'Gilgit Baltistan',
		       'slug' => 'gilgit-baltistan',
		       'country_id' => 1,
		    ],
		    [
		       'name' => 'Azad Jammu & Kashmir',
		       'slug' => 'azad-jammu-kashmir',
		       'country_id' => 1,
		    ],
		];

		Province::insert($data);
		}	else {
			echo "*Province* Table Already has Data\n";
		}
    }


    protected function createCities()
    {
    	if (City::count() == 0){
        $data = [
		    [
		       'name' => 'Lahore',
		       'slug' => 'lahore',
		       'province_id' => 2,
		    ],
		    [
		       'name' => 'Multan',
		       'slug' => 'multan',
		       'province_id' => 2,
		    ],
		];

		City::insert($data);
		}	else {
			echo "*City* Table Already has Data\n";
		}
    }
}
