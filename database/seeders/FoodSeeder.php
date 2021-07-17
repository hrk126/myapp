<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Food;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for( $cnt = 1; $cnt <= 2; $cnt++ ) { 
            Food::create([
                'name' => '定食' . $cnt,
                'price' => $cnt * 100,
                'image_file' => 'image_' . $cnt . '.jpg'
            ]);
        }
        Food::create([
            'name' => '定食3',
            'price' => 300
        ]);
    }
}
