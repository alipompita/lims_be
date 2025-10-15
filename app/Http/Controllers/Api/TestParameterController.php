<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TestParameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TestParameterController extends Controller
{
    public function index()
    {
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Test parameters fetched successfully',
        //     'timestamp' => now(),
        //     // 'data' => TestParameter::with('testType')->get(),
        // ]);

        try {
            $testParameters = TestParameter::with('testType')->get();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching test parameters.',
                'error' => $e->getMessage(),
            ], 500);
        }
        $testParameters = TestParameter::with('testType')->get();
        return response()->json($testParameters);
    }

    public function show($id)
    {
        $testParameter = TestParameter::with('testType')->findOrFail($id);
        return response()->json($testParameter);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_type_id' => 'required|exists:test_types,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('test_parameters')->where('test_type_id', $request->input('test_type_id'))
            ],
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'normal_range_min' => 'nullable|numeric',
            'normal_range_max' => 'nullable|numeric',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $testParameter = TestParameter::create([
                'test_type_id' => $request->test_type_id,
                'name' => strtolower($request->name),
                'description' => $request->description,
                'type' => $request->type,
                'unit' => $request->unit,
                'normal_range_min' => $request->normal_range_min,
                'normal_range_max' => $request->normal_range_max,

            ]);
            return response()->json([
                'success' => true,
                'message' => 'Test parameter created successfully',
                'data' => $testParameter,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the test parameter',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $testParameter = TestParameter::findOrFail($id);
        $validatedData = $request->validate([
            'test_type_id' => 'sometimes|required|exists:test_types,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|max:50',
            'unit' => 'nullable|string|max:50',
            'normal_range_min' => 'nullable|numeric',
            'normal_range_max' => 'nullable|numeric',
        ]);

        $testParameter->update($validatedData);
        return response()->json($testParameter);
    }

    public function destroy($id)
    {
        $testParameter = TestParameter::findOrFail($id);
        $testParameter->delete();
        return response()->json(null, 204);
    }

    public function testTypeParameters(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'test_type_id' => 'required|exists:test_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $testTypeId = $request->input('test_type_id');
        $parameters = TestParameter::where('test_type_id', $testTypeId)->get();
        return response()->json([
            'success' => true,
            'data' => $parameters,
        ]);
    }
}
