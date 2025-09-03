<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\TestWorksheet;
use App\Models\TestWorksheetSample;
use App\Models\Worksheet;
use GuzzleHttp\Psr7\Request;

class TestWorksheetService
{


    // public function createWorksheet(int $testTypeId): ?TestWorksheet
    public function createWorksheet(int $testTypeId)
    {

        // Step 1: eligible specimens for selected test_type
        try {
            // return ["point" => "before DB call"];
            // return DB::table('specimens as s')->get();
            $eligibleSpecimens = DB::table('specimens as s')
                ->whereIn('s.spectype', function ($query) use ($testTypeId) {
                    $query->select('spectype')
                        ->from('study_test_requirements')
                        ->where('test_type', $testTypeId);
                })
                ->leftJoin('test_worksheet_samples as sample', 's.labno', '=', 'sample.labno')
                ->leftJoin('worksheets as w', 'w.id', '=', 'sample.worksheet_id')
                ->where(function ($query) use ($testTypeId) {
                    // keep specimens that are not used in any worksheet for this test type
                    $query->whereNull('w.id')
                        ->orWhere('w.test_type_id', '!=', $testTypeId);
                })
                ->select('s.labno')
                ->distinct()
                ->get();
        } catch (\Exception $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }


        // return ["point" => "after eligible specimens", "eligibleSpecimens" => $eligibleSpecimens]; //debug

        if ($eligibleSpecimens->isEmpty()) {
            return null; // no eligible specimens, skip creation
        }
        // return ["eligibleSpecimens" => $eligibleSpecimens]; //debug

        // Step 2: create worksheet and attach specimens
        try {

            // return ["point" => "before transaction"];
            return DB::transaction(function () use ($testTypeId, $eligibleSpecimens) {
                // Create the worksheet
                // return ["point" => "inside transaction"];
                try {
                    $worksheet = Worksheet::create([
                        'worksheet_type' => 'T',
                        'test_type' => $testTypeId,
                        'code' => date('YmdHis') . rand(100, 999),
                    ]);
                } catch (\Exception $e) {
                    return ['error' => 'Failed to create worksheet: ' . $e->getMessage()];
                }

                // Attach specimens to worksheet
                foreach ($eligibleSpecimens as $specimen) {
                    TestWorksheetSample::create([
                        'worksheet_id' => $worksheet->id,
                        'labno' => $specimen->labno,
                        'test_type_id' => $testTypeId,
                    ]);
                }

                return $worksheet;
            });
        } catch (\Exception $e) {
            return null;
        }


        return null;
    }
}
