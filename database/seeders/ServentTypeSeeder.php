<?php

namespace Database\Seeders;

use App\Models\ServentType;
use Illuminate\Database\Seeder;

class ServentTypeSeeder extends Seeder
{
    public function run()
    {
       if (ServentType::count() == 0){
        $data = [
		    [
		       'title' => 'Butler',
		    ],
		    [
		       'title' => 'Housekeeper',
		    ],
		    [
		       'title' => 'Cook',
		    ],
		    [
		       'title' => 'House Maids',
		    ],
		    [
		       'title' => 'Nursey Maid&Nanny',
		    ],
		    [
		       'title' => 'Guard',
		    ],
		    [
		       'title' => 'Gardner',
		    ],
		];

		ServentType::insert($data);
		} else {
			echo "*ServentType* Table Already has Data\n";
		}

	}
}
