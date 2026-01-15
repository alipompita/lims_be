<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\StudyStorageRequirement;
use Illuminate\Validation\Rule;


class StorageRequirementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requirements = StudyStorageRequirement::with('study', 'spectype')->get();
        return response()->json([
            'success' => true,
            'data' => $requirements
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_id' => 'required|exists:studies,id',
            'aliqotes' => 'required|integer|min:1',
            'spectype' => [
                'required',
                'exists:specimen_types,id',
                Rule::unique('study_storage_requirements')->where(function ($query) use ($request) {
                    return $query->where('study_id', $request->study_id);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $storage_requirement = StudyStorageRequirement::create([
            'study_id' => $request->study_id,
            'aliqotes' => $request->aliqotes,
            'spectype' => $request->spectype,
        ]);

        if (!$storage_requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create storage requirement',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $storage_requirement,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $storage_requirement = StudyStorageRequirement::with('study', 'spectype')->find($id);
        if (!$storage_requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Storage requirement not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $storage_requirement,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'aliqotes' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $storage_requirement = StudyStorageRequirement::with('study', 'spectype')->find($id);
        if (!$storage_requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Storage requirement not found',
            ], 404);
        }

        $storage_requirement->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $storage_requirement,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $storage_requirement = StudyStorageRequirement::find($id);
        if (!$storage_requirement) {
            return response()->json([
                'success' => false,
                'message' => 'Storage requirement not found',
            ], 404);
        }

        $storage_requirement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Storage requirement deleted successfully',
        ]);
    }
}
