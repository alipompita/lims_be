<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TestType;
use Illuminate\Support\Facades\Validator;

class TestTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = TestType::select('id', 'label', 'name', 'description')
            ->with('parameters:id,test_type_id,name,unit,normal_range_min,normal_range_max');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $testTypes = $query->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $testTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255|unique:test_types,label',
            'name' => 'required|string|max:255|unique:test_types,name',
            'description' => 'nullable|string|max:500',
            'parameters' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $testType = TestType::create([
            'label' => $request->label,
            'name' => $request->name,
            'description' => $request->description,
            'parameters' => $request->parameters,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test type created successfully',
            'data' => $testType,
        ], 201);
    }

    public function show(TestType $testType)
    {
        return response()->json([
            'success' => true,
            'data' => $testType,
        ]);
    }

    public function update(Request $request, TestType $testType)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'sometimes|required|string|max:255|unique:test_types,label,' . $testType->id,
            'name' => 'sometimes|required|string|max:255|unique:test_types,name,' . $testType->id,
            'description' => 'nullable|string|max:500',
            'parameters' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $testType->update($request->only(['label', 'name', 'description', 'parameters']));

        return response()->json([
            'success' => true,
            'message' => 'Test type updated successfully',
            'data' => $testType,
        ]);
    }

    public function destroy(TestType $testType)
    {
        $testType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Test type deleted successfully',
        ]);
    }
}
