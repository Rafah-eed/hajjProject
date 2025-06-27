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
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
                'phone_number' => '5551234567',
                'role' => 'admin'
            ],
            [
                'first_name' => 'Safaa',
                'last_name' => 'Johnson',
                'email' => 'guide@example.com',
                'password' => Hash::make('guide'),
                'phone_number' => '5551234567',
                'role' => 'guide'
            ],
            [
                'first_name' => 'Safaa',
                'last_name' => 'Johnson',
                'email' => 'user@example.com',
                'password' => Hash::make('user'),
                'phone_number' => '5551234567',
                'role' => 'user'
            ],
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('superadmin'), // always hash passwords
                'phone_number' => '123456789',
                'role' => 'superAdmin'
            ],
        ]);
    }
}
