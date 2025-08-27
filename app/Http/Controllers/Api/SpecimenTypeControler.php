<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Models\SpecimenType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SpecimenTypeControler extends Controller
{
    public function index(Request $request)
    {



        $query = SpecimenType::select('id', 'code', 'code_label', 'label', 'description', 'transport_method', 'has_aliquot', 'is_placenta_tissue', 'total_aliquots', 'taken_from_blood', 'is_active', 'created_by', 'updated_by');


        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }




        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%");
            });
        }




        $specimenTypes = $query->orderBy('id', 'asc')->get();

        // return response()->json([
        //     'success' => true,
        //     'query' => $specimenTypes,
        // ]);

        return response()->json([
            'success' => true,
            'data' => $specimenTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:4|unique:specimen_types,code',
            'code_label' => 'required|string|max:4|unique:specimen_types,code_label',
            'label' => 'required|string|max:255|unique:specimen_types,label',
            'description' => 'nullable|string|max:255',
            'transport_method' => 'nullable|integer',
            'has_aliquot' => 'boolean',
            'is_placenta_tissue' => 'boolean',
            'total_aliquots' => 'integer',
            'taken_from_blood' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $specimenType = SpecimenType::create([
            'code' => $request->code,
            'code_label' => $request->code_label,
            'label' => $request->label,
            'description' => $request->description,
            'transport_method' => $request->transport_method,
            'has_aliquot' => $request->has_aliquot ?? false,
            'is_placenta_tissue' => $request->is_placenta_tissue ?? true,
            'total_aliquots' => $request->total_aliquots ?? 0,
            'taken_from_blood' => $request->taken_from_blood ?? true,
            'is_active' => $request->is_active ?? true,
        ]);

        $specimenType->load(['created_by:id, first_name, last_name, username']);

        return response()->json([
            'success' => true,
            'message' => 'Specimen type created successfully',
            'data' => $specimenType,
        ], 201);
    }

    public function show(SpecimenType $specimenType)
    {
        // $specimenType->load(['created_by:id, first_name, last_name, username', 'updated_by:id, first_name, last_name, username']);

        return response()->json([
            'success' => true,
            'data' => $specimenType,
        ]);
    }

    public function update(Request $request, SpecimenType $specimenType)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|max:4|unique:specimen_types,code,' . $specimenType->id,
            'code_label' => 'sometimes|required|string|max:4|unique:specimen_types,code_label,' . $specimenType->id,
            'label' => 'sometimes|required|string|max:255|unique:specimen_types,label,' . $specimenType->id,
            'description' => 'nullable|string|max:255',
            'transport_method' => 'nullable|integer',
            'has_aliquot' => 'boolean',
            'is_placenta_tissue' => 'boolean',
            'total_aliquots' => 'integer',
            'taken_from_blood' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $specimenType->update($request->only([
            'code',
            'code_label',
            'label',
            'description',
            'transport_method',
            'has_aliquot',
            'is_placenta_tissue',
            'total_aliquots',
            'taken_from_blood',
            'is_active'
        ]));

        $specimenType->load(['created_by:id, first_name, last_name, username', 'updated_by:id, first_name, last_name, username']);

        return response()->json([
            'success' => true,
            'message' => 'Specimen type updated successfully',
            'data' => $specimenType,
        ]);
    }

    public function destroy(SpecimenType $specimenType)
    {
        $specimenType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Specimen type deleted successfully',
        ]);
    }
}
