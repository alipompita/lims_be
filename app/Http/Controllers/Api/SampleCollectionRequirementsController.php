<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SampleCollectionRequirement;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SampleCollectionRequirementsController extends Controller
{
    public function index()
    {
        try {
            $requirements = SampleCollectionRequirement::with('study', 'studyAccForm')->get();
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e
            ], 422);
        }
        return response()->json([
            'success' => true,
            'data' => $requirements
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_acc_form_id' => ['required', 'exists:study_acc_forms,id'],
            'spectype' => ['required', 'exists:specimen_types,id', Rule::unique('sample_collection_requirements', 'spectype')->where(function ($query) use ($request) {
                return $query->where('study_acc_form_id', $request->input('study_acc_form_id'));
            })],
            'volume_required' => ['nullable', 'numeric'],
            'volume_unit' => ['nullable', 'string'],
            'recommended_shipping_temperature' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $requirement = SampleCollectionRequirement::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $requirement,
        ], 201);
    }

    public function show($id)
    {
        $requirement = SampleCollectionRequirement::find($id);
        if (!$requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Sample Collection Requirement not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $requirement,
        ]);
    }
}
