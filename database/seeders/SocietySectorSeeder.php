<?php

namespace Database\Seeders;

use App\Models\Society;
use App\Models\SocietySector;
use Illuminate\Database\Seeder;

class SocietySectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  		$this->createSocietes();
	    $this->createSectors();
    }

    protected function createSocietes()
    {
    	if (Society::count() == 0){
	        $data = [
			    [
			       'code' => 'ROYL',
			       'name' => 'Royal orchard',
			       'slug' => 'royal-orchard',
			       'country_id' => 1,
			       'province_id' => 2,
			       'city_id' => 2,
			       'address' => 'Multan Pakistan',
			    ],
			];

			Society::insert($data);
		} else {
			echo "*Society* Table Already has Data\n";
		}
    }

    protected function createSectors() {
    	if (SocietySector::count() == 0){
        $data = [
		    [
		       'society_id' => 1,
		       'sector_name' => 'First Block',
		    ],
		    [
		       'society_id' => 1,
		       'sector_name' => 'Second Block',
		    ],
		    [
		       'society_id' => 1,
		       'sector_name' => 'Third Block',
		    ],

		];

		SocietySector::insert($data);
		}	else {
			echo "*SocietySector* Table Already has Data\n";
		}
    }
}

