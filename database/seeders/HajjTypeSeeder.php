<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HajjTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('hajj_types')->insert([
            [
                'type' => 'حج الإفراد',
            ],
            [
                'type' => 'حج القران',
            ],
            [
                'type' => 'حج التمتع',
            ],
        ]);
    }
}
