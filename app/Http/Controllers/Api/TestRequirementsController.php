<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudyTestRequirement;
use App\Models\Study;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TestRequirementsController extends Controller
{

    public function index()
    {

        $requirements = StudyTestRequirement::with('study', 'spectype')->get();
        return response()->json([
            'success' => true,
            'data' => $requirements
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'study_id' => 'required|exists:studies,id',
            'spectype' => 'required|exists:specimen_types,id',
            'test_type' => 'required|integer|exists:test_types,',
            // new rule unique test_type per study_id and spectype
            'test_type' => [
                'required',
                'integer',
                Rule::unique('study_test_requirements')->where(function ($query) use ($request) {
                    return $query->where('study_id', $request->study_id)
                        ->where('spectype', $request->spectype);
                }),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $test_requirement = StudyTestRequirement::create([
            'study_id' => $request->study_id,
            'test_type' => $request->test_type,
            'spectype' => $request->spectype,
        ]);

        if (!$test_requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create test requirement',
            ], 500);
        } else {
            return response()->json([
                'success' => true,
                'data' => $test_requirement,
            ], 201);
        }
    }
}
