<?php

namespace Database\Seeders;

use App\Models\HandyServiceType;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class HandyServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (HandyServiceType::count() == 0){
        $data = [
		    [
		       'title' => 'Electricity',
		    ],
		    [
		       'title' => 'Plumber',
		    ],
		    [
		       'title' => 'AC Technician',
		    ],
		    [
		       'title' => 'Mason',
		    ],
		    [
		       'title' => 'Electricity',
		    ],
		    [
		       'title' => 'Carpenter',
		    ],
		    [
		       'title' => 'Painter',
		    ],
		    [
		       'title' => 'Cleaner',
		    ],
		    [
		       'title' => 'Construction',
		    ],
		    [
		       'title' => 'Glass Worker',
		    ],
		    [
		       'title' => 'Ceiling Worker',
		    ],
		    [
		       'title' => 'Welder',
		    ]
		];

			HandyServiceType::insert($data);
		}

		$this->createVehicleTypes(); 
	}


	protected function createVehicleTypes()
    {
        if (VehicleType::count() == 0){
        $data = [
            [
               'title' => 'Bus',
               'slug' => 'bus',
            ],
            [
               'title' => 'Truck',
               'slug' => 'truck',
            ],
            [
               'title' => 'Car',
               'slug' => 'car',
            ],
        ];

        VehicleType::insert($data);
        }   else {
            echo "*VehicleType* Table Already has Data\n";
        }
    }
}
