<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ShipmentSpecimen;

class ShipmentSpecimenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getShipmentSpecimens(int $shipmentId)
    {
        $specimens = ShipmentSpecimen::where('shipment_id', $shipmentId)->get();
        return response()->json([
            'success' => true,
            'data' => $specimens
        ], 200);
    }

    public function getShipmentSpecimensByBox(int $shipmentId, int $boxNumber)
    {
        try {
            $specimens = ShipmentSpecimen::where('shipment_id', $shipmentId)->with('specimen')
                ->where('box_number', $boxNumber)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $specimens
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the shipment specimens.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shipment_id' => 'required|exists:shipments,id',
                'labno' => 'required|exists:specimens,labno',
                'shipment_purpose' => 'nullable|string|in:Test,Storage',
                'box_number' => 'required|integer',
                'position' => [
                    'required',
                    'string',
                    'max:4',
                    Rule::unique('shipment_specimen')->where(function ($query) use ($request) {
                        return $query
                            ->where('shipment_id', $request->shipment_id)
                            ->where('box_number', $request->box_number);
                    }),
                ],
                'qty' => 'required|numeric',
                'unit' => 'required|string|max:8'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $shipmentSpecimen = ShipmentSpecimen::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Shipment specimen created successfully',
                'data' => $shipmentSpecimen
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while validating the request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
