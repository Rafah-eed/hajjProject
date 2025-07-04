<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('hotels')->insert([
            [
                'office_id' => 2,
                'hotel_name' =>'النجوم',
                'rate' => "8",
                'address' => 'مكة',
            ],
            [
                'office_id' => 2,
                'hotel_name' =>'المجد',
                'rate' => "4",
                'address' => 'المدينة',
            ],
            [
                'office_id' => 1,
                'hotel_name' =>'النجوم',
                'rate' => "8",
                'address' => 'مكة',
            ],
        ]);


        DB::table('rooms')->insert([
            [
                'hotel_id' => 2,
                'room_type' =>'غرفة مفردة',
                'price' => "2000.08"
            ],
            [
                'hotel_id' => 2,
                'room_type' =>'غرفة ثنائية',
                'price' => "2000.08"
            ],[
                'hotel_id' => 2,
                'room_type' =>'غرفة ثلاثية',
                'price' => "2000.08"
            ],
            [
                'hotel_id' => 2,
                'room_type' =>'غرفة رباعية',
                'price' => "2000.08"
            ],
            [
                'hotel_id' => 2,
                'room_type' =>'غرفة جناح خاص',
                'price' => "2000.08"
            ],
            [
                'hotel_id' => 1,
                'room_type' =>'غرفة مفردة',
                'price' => "2000.08"
            ],
            [
                'hotel_id' => 1,
                'room_type' =>'غرفة ثنائية',
                'price' => "2000.08"
            ],
            [
                'hotel_id' => 3,
                'room_type' =>'غرفة رباعية',
                'price' => "2000.08"
            ],
            [
                'hotel_id' => 3,
                'room_type' =>'جناح خاص',
                'price' => "2000.08"
            ],
        ]);
    }
}