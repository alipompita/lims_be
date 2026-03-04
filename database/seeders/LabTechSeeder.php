<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LabTechSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'JD',
            'email' => 'john.doe@meiru.mw',
            'password' => Hash::make('12345678'),
            'role' => 'lab_tech',
            'default_site_id' => 1,
            'is_active' => true,
        ]);
        User::create([
            'first_name' => 'James',
            'last_name' => 'Bond',
            'username' => 'JB',
            'email' => 'james.bond@meiru.mw',
            'password' => Hash::make('12345678'),
            'role' => 'lab_tech',
            'default_site_id' => 2,
            'is_active' => true,
        ]);
    }
}
