<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudyParticipant;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Nullable;

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
            'study_id' => 'nullable|exists:studies,id',
            'stid' => 'required|string|max:50|unique:study_participants,stid',
            'initials' => 'nullable|string|max:10',
            'sex' => 'integer|in:0,1',
            'dob' => 'nullable|date',
            'reascoll' => 'nullable|string',
        ]);

        $validator->sometimes('study_id', 'required', function ($input) {
            return empty($input->reascoll);
        });

        $validator->after(function ($validator) use ($request) {
            if (empty($request->study_id) && !empty($request->reascoll)) {
                $study = \App\Models\Study::where('code', $request->reascoll)->first();

                if (!$study) {
                    $validator->errors()->add('reascol', 'Invalid reascol: no matching study found.');
                } else {
                    // inject study_id into the request
                    $request->merge(['study_id' => $study->id]);
                }
            }
        });

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

    // bulk store for storing multiple rows at the same time
    public function bulkStore(Request $request)
    {
        $rows = $request->input('participants', []);
        $success = [];
        $errors = [];

        foreach ($rows as $index => $row) {
            $row = (array) $row;

            $validator = Validator::make($row, [
                'study_id' => 'nullable|exists:studies,id',
                'stid' => 'required|string|max:50|unique:study_participants,stid',
                'initials' => 'nullable|string|max:10',
                'sex' => 'integer|in:0,1',
                'dob' => 'nullable|date',
                'reascoll' => 'nullable|string',
            ]);

            $validator->sometimes('study_id', 'required', function ($input) {
                return empty($input->reascoll);
            });

            $validator->after(function ($validator) use (&$row) {
                if (empty($row['study_id']) && !empty($row['reascoll'])) {
                    $study = \App\Models\Study::where('code', $row['reascoll'])->first();

                    if (!$study) {
                        $validator->errors()->add('reascoll', 'Invalid reascoll: no matching study found.');
                    } else {
                        // inject study_id into the row
                        $row['study_id'] = $study->id;
                    }
                }
            });

            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index,
                    'data' => $row,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            try {
                $study_participant = StudyParticipant::create([
                    'study_id' => $row['study_id'] ?? null,
                    'stid' => $row['stid'],
                    'initials' => $row['initials'],
                    'sex' => $row['sex'] ?? null,
                    'dob' => $row['dob'] ?? null,
                    'created_by' => auth('sanctum')->id(),
                ]);
                $success[] = $study_participant;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $index,
                    'data' => $row,
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        return response()->json([
            'summary' => [
                'total' => count($rows),
                'successful' => count($success),
                'failed' => count($errors),
            ],
            'failed_rows' => $errors,
        ], 200);
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
