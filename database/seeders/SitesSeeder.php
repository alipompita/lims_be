<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Site::create([
            'id' => '1',
            'name' => 'Karonga',
        ]);
        Site::create([
            'id' => '2',
            'name' => 'Lilongwe',
        ]);
    }
}
