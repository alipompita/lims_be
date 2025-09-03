<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\TestWorksheet;
use App\Models\TestWorksheetSample;
use GuzzleHttp\Psr7\Request;

class TestWorksheetService
{


    // public function createWorksheet(int $testTypeId): ?TestWorksheet
    public function createWorksheet(int $testTypeId)
    {

        // Step 1: eligible specimens for selected test_type
        $eligibleSpecimens = DB::table('specimens')
            ->whereIn('spectype', function ($query) use ($testTypeId) {
                $query->select('spectype')
                    ->from('study_test_requirements')
                    ->where('test_type', $testTypeId);
            })
            ->whereNotIn('labno', function ($query) use ($testTypeId) {
                $query->select('sample.labno')
                    ->from('test_worksheet_samples as sample')
                    ->leftJoin('worksheets as worksheet', 'worksheet.id', '=', 'sample.worksheet_id')
                    ->where('test_type_id', $testTypeId);
            })
            ->get();



        if ($eligibleSpecimens->isEmpty()) {
            return null; // no eligible specimens, skip creation
        }
        // return ["eligibleSpecimens" => $eligibleSpecimens]; //debug

        // Step 2: create worksheet and attach specimens
        try {
            return DB::transaction(function () use ($testTypeId, $eligibleSpecimens) {
                // Create the worksheet
                // return ["point" => "inside transaction"];
                try {
                    $worksheet = TestWorksheet::create([
                        'worksheet_type' => 'T',
                        'test_type' => $testTypeId,
                        'code' => date('YmdHis') . rand(100, 999),
                    ]);
                } catch (\Exception $e) {
                    return null;
                }



                return null;

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
