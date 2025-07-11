<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            [
                'name' => 'المبرور',
                'address' => 'برج تالا_ المزة',
                'license_number' => 1001,
                'office_email' =>'office1@gmail.com',
                'office_password' => 'office1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الراشدين',
                'address' => 'الميدان_ باب مصلى',
                'license_number' => 1002,
                'office_email' =>'office2@gmail.com',
                'office_password' => 'office2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'بركة',
                'address' => 'ألو رمانة _ جانب جامع الحسن',
                'license_number' => 1003,
                'office_email' =>'office3@gmail.com',
                'office_password' => 'office3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}