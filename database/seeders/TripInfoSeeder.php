<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TripInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('hotel_trips')->insert([
            [
                'trip_id' => 1,
                'hotel_id' => 1,
                'office_id' => 1,
                'place' => "Makka",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'hotel_id' => 1,
                'office_id' => 1,
                'place' => "Madina",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('transport_trips')->insert([
            [
                'trip_id' => 1,
                'transport_id' => 1,
                'office_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 1,
                'transport_id' => 2,
                'office_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trip_id' => 2,
                'transport_id' => 2,
                'office_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
