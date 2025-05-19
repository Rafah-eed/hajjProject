<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trips')->insert([
            [ 
                'type' => 'umrah',
                'regiment_name' => 'Al-Fatih Regiment',
                'days_num_makkah' => 5,
                'days_num_madinah' => 3,
                'price' => 2500.00,
                'start_date' => '2025-06-01',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'type' => 'hajjQ',
                'regiment_name' => 'Al-Nour Regiment',
                'days_num_makkah' => 7,
                'days_num_madinah' => 4,
                'price' => 4000.00,
                'start_date' => '2025-07-10',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // add more example trips
        ]);
    }
}
