<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('studies')->delete();

        DB::table('studies')->insert(array(
            0 =>
            array(
                'id' => 1,
                'code' => 'GM',
                'title' => 'Generation Malawi',
                'description' => NULL,
                'created_at' => '2025-08-20 09:31:23',
                'updated_at' => '2025-08-20 09:31:23',
                'is_active' => 1,
            ),
            1 =>
            array(
                'id' => 2,
                'code' => 'LTC',
                'title' => 'Long-term Conditions',
                'description' => NULL,
                'created_at' => '2025-08-20 09:32:30',
                'updated_at' => '2025-08-20 09:32:30',
                'is_active' => 1,
            ),
            2 =>
            array(
                'id' => 3,
                'code' => 'CVD',
                'title' => 'Cardiovascular Disease',
                'description' => NULL,
                'created_at' => '2025-08-20 09:33:38',
                'updated_at' => '2025-08-20 09:33:38',
                'is_active' => 1,
            ),
            3 =>
            array(
                'id' => 4,
                'code' => 'CHW',
                'title' => 'Chiwindi',
                'description' => NULL,
                'created_at' => '2025-08-20 09:35:44',
                'updated_at' => '2025-08-20 09:35:44',
                'is_active' => 1,
            ),
        ));
    }
}
