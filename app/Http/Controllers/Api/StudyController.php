<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Study;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    public function index(Request $request)
    {
        $query = Study::select('id', 'code', 'title', 'description', 'is_active');

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $studies = $query->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $studies,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:studies,code',
            'title' => 'required|string|max:255|unique:studies,title',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $study = Study::create([
            'code' => $request->code,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Study created successfully',
            'data' => $study,
        ], 201);
    }

    public function show(Study $study)
    {
        return response()->json([
            'success' => true,
            'data' => $study,
        ]);
    }

    public function update(Request $request, Study $study)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|max:10|unique:studies,code,' . $study->id,
            'title' => 'sometimes|required|string|max:255|unique:studies,title,' . $study->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $study->update($request->only(['code', 'title', 'description', 'is_active']));

        return response()->json([
            'success' => true,
            'message' => 'Study updated successfully',
            'data' => $study,
        ]);
    }

    public function destroy(Study $study)
    {
        $study->delete();

        return response()->json([
            'success' => true,
            'message' => 'Study deleted successfully',
        ]);
    }
}
