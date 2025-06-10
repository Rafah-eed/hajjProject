<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(OfficesTableSeeder::class);
        $this->call(TripTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        // \App\Models\User::factory(10)->create();
    }
}
