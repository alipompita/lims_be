<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SampleReceipt;
use Illuminate\Support\Facades\Validator;

class SampleReceptionController extends Controller
{
    public function index(Request $request)
    {
        $query = SampleReceipt::with(['study', 'specimenType', 'entryBy']);

        // Filter by rejection status
        if ($request->has('rejected')) {
            $isRejected = $request->boolean('rejected');
            if ($isRejected) {
                $query->rejected();
            } else {
                $query->notRejected();
            }
        }

        $sampleReceipts = $query->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $sampleReceipts,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_id' => 'required|exists:studies,id',
            'basefol' => 'nullable|string|max:50',
            'stid' => 'nullable|string|max:50',
            'spectype' => 'required|exists:specimen_types,id',
            'specno' => 'required|string|max:50|unique:sample_receipts,specno',
            'datecol' => 'required|date',
            'dateinlab' => 'required|date',
            // 'entry_by' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $sample_receipt = SampleReceipt::create([
            'study_id' => $request->study_id,
            'basefol' => $request->basefol,
            'stid' => $request->stid,
            'spectype' => $request->spectype,
            'specno' => $request->specno,
            'datecol' => $request->datecol,
            'dateinlab' => $request->dateinlab,
            'entry_by' => $request->entry_by,
        ]);

        return response()->json([
            'success' => true,
            'data' => $sample_receipt,
        ], 201);
    }
}
