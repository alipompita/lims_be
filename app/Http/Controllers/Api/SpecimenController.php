<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specimen;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
}
