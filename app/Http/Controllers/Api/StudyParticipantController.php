<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudyParticipant;
use Illuminate\Support\Facades\Validator;

class StudyParticipantController extends Controller
{
    public function index(Request $request)
    {

        $query = StudyParticipant::select('stid', 'initials', 'sex', 'dob', 'study_id', 'created_at', 'updated_at')
            ->with('study:id,code,title');

        // Filter by study ID
        if ($request->has('study_id')) {
            $query->where('study_id', $request->study_id);
        }



        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('stid', 'like', "%{$search}%")
                    ->orWhere('initials', 'like', "%{$search}%");
            });
        }



        $participants = $query->orderBy('stid', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $participants,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_id' => 'required|exists:studies,id',
            'stid' => 'required|string|max:50|unique:study_participants,stid',
            'initials' => 'required|string|max:10',
            'sex' => 'integer|in:0,1',
            'dob' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $participant = StudyParticipant::create([
            'study_id' => $request->study_id,
            'stid' => $request->stid,
            'initials' => $request->initials,
            'sex' => $request->sex,
            'dob' => $request->dob,
            'created_by' => auth('sanctum')->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $participant,
        ], 201);
    }

    public function show(StudyParticipant $study_participant)
    {
        return response()->json([
            'success' => true,
            'data' => $study_participant,
        ]);
    }

    public function update(Request $request, StudyParticipant $study_participant)
    {
        $validator = Validator::make($request->all(), [
            'study_id' => 'exists:studies,id',
            'initials' => 'string|max:10',
            'sex' => 'integer|in:0,1',
            'dob' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $study_participant->update([
            'study_id' => $request->study_id ?? $study_participant->study_id,
            'initials' => $request->initials ?? $study_participant->initials,
            'sex' => $request->sex ?? $study_participant->sex,
            'dob' => $request->dob ?? $study_participant->dob,
            'updated_by' => auth('sanctum')->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $study_participant,
        ]);
    }
}
