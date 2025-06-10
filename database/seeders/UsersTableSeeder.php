<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'mazen',
                'last_name' => 'Doe',
                'email' => 'mazen@example.com',
                'password' => Hash::make('password'), // always hash passwords
                'phone_number' => '123456789',
                'role' => 'admin'
            ],
            [
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'email' => 'alice@example.com',
                'password' => Hash::make('alice'),
                'phone_number' => '9876543210',
                'role' => 'admin'
            ],
            [
                'first_name' => 'Safaa',
                'last_name' => 'Johnson',
                'email' => 'safaa@example.com',
                'password' => Hash::make('safaa'),
                'phone_number' => '5551234567',
                'role' => 'admin'
            ],
        ]);
    }
}
