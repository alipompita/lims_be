<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specimen;
use App\Models\Study;
use App\Models\StudyParticipant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Exception;

class SpecimenController extends Controller
{
    //

    public function index()
    {

        try {
            $specimens = Specimen::with('studyParticipant')->get();
            return response()->json($specimens);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching specimens',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stid' => 'required|exists:study_participants,stid',
            'specno' => 'required|string|max:7|min:7|unique:specimens,specno',
            'labno' => 'required|string|max:7|min:7|unique:specimens,labno',
            'spectype' => 'required|exists:specimen_types,id',
            'datecol' => 'required|date',
            'accForm' => 'required|string|max:64',
            'repeat_sample' => 'boolean',
            'pregnant' => 'boolean',
            'curmens' => 'boolean',
            'mens2d' => 'boolean',
            'basefoll' => 'boolean',
            'fast' => 'boolean',
            'venepunc' => 'boolean',
            'volume' => 'numeric',
            'tubes' => 'integer',
            'stooltype' => 'integer',
            'stoolusual' => 'integer',
            'spectime' => 'time',
            'timeprod' => 'time',
            'timeint' => 'time',
            'iohexol' => 'boolean',
            'dateinlab' => 'date',
            'timeinlab' => 'time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $specimen = Specimen::create($validator->validated());
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating specimen',
                'error' => $e->getMessage(),
            ], 500);
        }



        return response()->json([
            'success' => true,
            'data' => $specimen,
        ], 201);
    }

    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specimens' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $rows = $request->input('specimens', []);
        $success = [];
        $errors = [];

        foreach ($rows as $index => $row) {
            $row = (array) $row;

            $validator = Validator::make($row, [
                'stid' => 'required|exists:study_participants,stid',
                'specno' => 'required|string|max:7|min:7|unique:specimens,specno',
                'labno' => 'required|string|max:7|min:7|unique:specimens,labno',
                'spectype' => 'required|exists:specimen_types,id',
                'datecol' => 'required|date',
                'accForm' => 'required|string|max:64',
                'repeat_sample' => 'boolean',
                'pregnant' => 'boolean',
                'curmens' => 'boolean',
                'mens2d' => 'boolean',
                'basefoll' => 'boolean',
                'fast' => 'boolean',
                'venepunc' => 'boolean',
                'volume' => 'numeric',
                'tubes' => 'integer',
                'stooltype' => 'integer',
                'stoolusual' => 'integer',
                'spectime' => 'time',
                'timeprod' => 'time',
                'timeint' => 'time',
                'iohexol' => 'boolean',
                'dateinlab' => 'date',
                'timeinlab' => 'time',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index,
                    'data' => $row,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            try {
                $specimen = Specimen::create($row);
                $success[] = $specimen;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $index,
                    'data' => $row,
                    'errors' => [$e->getMessage()],
                ];
            }
        }
        return response()->json([
            'summary' => [
                'total' => count($rows),
                'successful' => count($success),
                'failed' => count($errors),
            ],
            'failed_rows' => $errors,
        ], 200);
    }

    public function testConnection()
    {
        try {
            // Simple query to test database connection
            DB::connection()->getPdo();
            return response()->json([
                'success' => true,
                'message' => 'Database connection is working',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Specimen $spec)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $spec,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ] . 404);
        }
    }

    public function LoadSpecimen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'STID' => 'required|string|max:7|min:7',
            'SPECNO' => 'required|string|max:7|min:7|unique:specimens,specno',
            'LABNO' => 'required|string|max:7|min:6|unique:specimens,labno',
            'SPECTYPE' => 'required|exists:specimen_types,id',
            'DATECOLL' => 'required|date',
            'ACCFORM' => 'required|string|max:64',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // check if study participant exists
        $participant = \App\Models\StudyParticipant::where('stid', $request->STID)->first();

        if (!$participant) {
            $accForm = \App\Models\StudyAccForm::where('code', $request->ACCFORM)->first();
            if (!$accForm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not determine study for this participant using provided accForm code: ' . $request->ACCFORM,
                ], 404);
            }

            try {
                $participant = \App\Models\StudyParticipant::create([
                    'stid' => $request->STID,
                    'study_id' => $accForm->study_id,
                    'initials' => $request->PARTICIPANTINITIALS ?? null,
                    'sex' => $request->SEX ?? null,
                    'dob' => $request->DOB_YEAR . '-' . $request->DOB_MONTH . '-' . $request->DOB_DAY ?? null,
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating study participant: ' . $e->getMessage(),
                ], 500);
            }
        }

        try {
            $specimen = Specimen::create([
                'labno' => $request->LABNO,
                'stid' => $participant->id,
                'specno' => $request->SPECNO,
                'spectype' => $request->SPECTYPE,
                'cno' => $request->CNO ?? null,
                'accForm' => $request->ACCFORM ?? null,
                'repeat_sample' => $request->RPTSAMPLE ?? null,
                'pregnant' => $request->PREG ?? null,
                'curmens' => $request->CURMENS ?? null,
                'mens2d' => $request->MENS2D ?? null,
                'basefoll' => $request->BASEFOLL ?? null,
                'fast' => $request->FAST ?? null,
                'venepunc' => $request->VENE ?? null,
                'volume' => $request->VOL ?? null,
                'tubes' => $request->TUBENUM ?? null,
                'stooltype' => $request->STOOLTYPE ?? null,
                'stoolusual' => $request->STOOLUSUAL ?? null,
                // 'spectime' => $request->SPECTIME ?? null,
                'datecol' => $request->DATECOLL ?? null,
                'timeprod' => $request->TIMEPROD ?? null,
                'timeint' => $request->TIMEINT ?? null,
                'iohexol' => $request->IOHEX ?? null,
                'dateinlab' => $request->DATEINLAB ?? null,
                'timeinlab' => $request->TIMEINLAB ?? null,
                'staffcode' => $request->STAFFCODE ?? null,
                // 'labstaff' => $request->LABSTAFF ?? null,
                // 'checker' => $request->CHECKER ?? null,
                'rcdr' => $request->RCDR ?? null,
                'version' => $request->VERSION ?? null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating specimen: ' . $e->getMessage(),
            ], 500);
        }


        return response()->json([
            'success' => true,
            'message' => 'Specimen Added Successfully',
            'specimen_details' => [
                'labno' => $specimen->labno,
                'specno' => $specimen->specno,
                'stid' => $participant->stid,
                'accForm' => $specimen->accForm,
                'spectype' => $specimen->spectype
            ]
        ], 200);
    }
}
