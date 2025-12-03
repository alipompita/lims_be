<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudyAccForm;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudyAccFormContoller extends Controller
{

    public function index()
    {
        $forms = StudyAccForm::with('study', 'sampleCollectionRequirements')->get();
        return response()->json([
            'success' => true,
            'data' => $forms
        ]);
    }

    public function show($id)
    {
        $form = StudyAccForm::find($id);
        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $form,
        ]);
    }

    public function destroy($id)
    {
        $form = StudyAccForm::find($id);
        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found',
            ], 404);
        }

        $form->delete();

        return response()->json([
            'success' => true,
            'message' => 'Form deleted successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        $form = StudyAccForm::find($id);
        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'form_name' => ['sometimes', 'string', 'max:16', Rule::unique('study_acc_forms', 'form_name')->where('study_id', $form->study_id)->ignore($form->id)],
            'form_description' => 'nullable|string|max:256',
            'is_followup' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $form->update($request->only(['form_name', 'form_description', 'is_followup']));

        return response()->json([
            'success' => true,
            'data' => $form,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_id' => 'required|exists:studies,id',
            'form_name' => ['required', 'string', 'max:16', Rule::unique('study_acc_forms', 'form_name')->where('study_id', $request->study_id)],
            'form_description' => 'nullable|string|max:256',
            'is_followup' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $form = StudyAccForm::create([
            'study_id' => $request->study_id,
            'form_name' => $request->form_name,
            'form_description' => $request->form_description,
            'is_followup' => $request->is_followup ?? false,
        ]);


        return response()->json([
            'success' => true,
            'data' => $form,
        ], 201);
    }
}
