<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShipmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipments = Shipment::all();
        return response()->json([
            "success" => true,
            "data" => $shipments,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_site_id' => 'integer|required|exists:sites,id',
            'to_site_id' => [
                'integer',
                'required',
                'exists:sites,id',
                'different:from_site_id',
                Rule::unique('shipments')->where(function ($query) use ($request) {
                    return $query->where('from_site_id', $request->from_site_id)
                        ->where('posted', false);
                }),
            ],
            'shipped_by' => 'integer|exists:users,id',
            'date_shipped' => 'date',
            'posted' => 'boolean'
        ], [
            'to_site_id.unique' => "There is already an unposted shipment from this site to the selected destination.",
            'to_site_id.different' => "The destination site must be different from the source site."
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
            ], 422);
        }
        try {
            $shipment = Shipment::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "errors" => $e->getMessage(),
            ], 422);
        }


        if ($shipment) {
            return response()->json([
                'success' => true,
                'message' => "Shipment Created Successfully!",
                'data' => $shipment,
            ], 201);
        } else {
            return response()->json([
                'success' => False,
                'message' => "Unable to create Shipment, please try again later!",
                'data' => $shipment,
            ], 422);
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
