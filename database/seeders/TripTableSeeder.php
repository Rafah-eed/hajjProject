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
    public function run(): void
    {
        DB::table('trips')->insert([
            [
                'office_id' => 1,
                'type' => 'umrah',
                'regiment_name' => 'Al-Fatih Regiment',
                'days_num_makkah' => 5,
                'days_num_madinah' => 3,
                'price' => 2500.00,
                'start_date' => '2025-06-01',
                'end_date' => '2025-07-10',
                'is_active' => true,
                'numOfReservations' => 10,
                'trip_code' => 12345 ,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_id' => 2,
                'type' => 'hajj',
                'regiment_name' => 'Al-Nour Regiment',
                'days_num_makkah' => 7,
                'days_num_madinah' => 4,
                'price' => 4000.00,
                'start_date' => '2025-07-10',
                'end_date' => '2025-07-10',
                'is_active' => true,
                'numOfReservations' => 10,
                'trip_code' => 12346 ,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_id' => 3,
                'type' => 'hajj',
                'regiment_name' => 'Baraka Regiment',
                'days_num_makkah' => 4,
                'days_num_madinah' => 2,
                'price' => 2000.00,
                'start_date' => '2026-01-20',
                'end_date' => '2025-07-10',
                'is_active' => false,
                'numOfReservations' => 20,
                'trip_code' => 12245 ,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


    }
}