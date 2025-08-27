<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpecimenType;

class SpecimenTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('role', 'admin')->first();

        if (!$adminUser) {
            $this->command->error('Admin user not found. Please run AdminUserSeeder first.');
            return;
        }

        $specimenTypes = [
            [
                'code_label' => 'A',
                'code' => '1',
                'label' => 'Blood (Dried spot)',
                'description' => 'Dried bloodspot from finger prick or heel prick',
                'transport_method' => 1,
                'has_aliquot' => false,
                'is_placenta_tissue' => false,
                'total_aliquots' => 0,
                'taken_from_blood' => true,
                'is_active' => true,
                'created_by' => $adminUser->id,
            ]
        ];

        foreach ($specimenTypes as $typeData) {
            SpecimenType::create(array_merge($typeData, [
                'created_by' => $adminUser->id,
                'is_active' => true,
            ]));
        }
    }
}
