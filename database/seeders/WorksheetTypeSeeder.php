<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorksheetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('worksheet_types')->insert([
            ['type' => 'S', 'description' => 'Storage'],
            ['type' => 'T', 'description' => 'Test Worksheet'],
        ]);
    }
}
