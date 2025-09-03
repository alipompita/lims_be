<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worksheet;
use Illuminate\Http\Request;
use App\Models\StorageWorksheet;
use App\Models\TestWorksheet;
use App\Services\TestWorksheetService;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Event\Code\Test;

class WorksheetController extends Controller
{
    protected $service;

    public function index(Request $request)
    {
        $query = Worksheet::select('id', 'test_type_id', 'created_at', 'updated_at');

        // filter by worksheet_type
        if ($request->has('test_type_id')) {
            $query->where('test_type_id', $request->test_type_id);
        }

        try {
            $worksheets = $query->orderBy('id', 'asc')->get();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
        // $worksheets = $query->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Worksheets retrieved successfully',
            'data' => $worksheets,
        ]);
    }

    public function __construct()
    {
        $this->service = new TestWorksheetService();
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'worksheet_type' => 'required|in:T,S',
        ]);

        if ($request->worksheet_type === 'T') {
            $validator = Validator::make($request->all(), [
                'test_type_id' => 'required|exists:test_types,id',
            ]);
            // $worksheet = $this->service->createWorksheet($request->test_type_id);
            try {
                // $worksheet = TestWorksheet::create($request->all());
                // $worksheet = $this->service->createWorksheet($request->test_type_id);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                ], 500);
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $worksheet = null;

        if ($request->worksheet_type === 'S') {
            try {
                $worksheet = Worksheet::create([
                    'worksheet_type' => 'S',
                    'code' => date('YmdHis') . rand(100, 999),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                ], 500);
            }
        } else {

            // look for eligible specimens and create worksheet if any
            $worksheet = $this->service->createWorksheet($request->test_type_id);

            if ($worksheet === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'No eligible specimens found for the selected test type. Worksheet not created.',
                ], 400);
            }
            return response()->json([
                'success' => true,
                'message' => 'Worksheet created successfully at top',
                'data' => $worksheet,
            ], 201);
        }

        return response()->json([
            'success' => true,
            'message' => 'Worksheet created successfully',
            'data' => $worksheet,
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
