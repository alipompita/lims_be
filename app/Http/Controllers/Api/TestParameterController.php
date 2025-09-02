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

        $testParameter = TestParameter::create([
            'test_type_id' => $request->test_type_id,
            'name' => $request->name,
            'description' => $request->description,

        ]);
        return response()->json([
            'success' => true,
            'message' => 'Test parameter created successfully',
            'data' => $testParameter,
        ], 201);
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
}
