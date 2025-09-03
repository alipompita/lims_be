<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TestWorksheetService;
use Illuminate\Support\Facades\Validator;

class WorksheetController extends Controller
{
    protected $service;

    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Index method called',
        ]);
    }

    public function __construct()
    {
        $this->service = new TestWorksheetService();
    }

    public function store(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Store method called',
        ]);
        $validator = Validator::make($request->all(), [
            'test_type_id' => 'required|exists:test_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Validation passed',
        ]);
    }

    public function storeWorksheet(Request $request)
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

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Validation passed',
        // ]);

        try {

            $worksheet = $this->service->createWorksheet($request->test_type_id);

            return response()->json([
                'success' => true,
                'message' => 'Worksheet created successfully',
                'data' => $worksheet,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }


        if (!$worksheet) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create worksheet',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Worksheet created successfully',
            'data' => $worksheet,
        ], 201);
    }
}
