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

    public function getOutgoingShipments()
    {
        $user_site_id = auth('sanctum')->user()->default_site_id;
        $shipments = Shipment::where('from_site_id', $user_site_id)->get();
        return response()->json([
            "success" => true,
            "data" => $shipments,
            "site_id" => $user_site_id
        ], 200);
    }

    public function getIncomingShipments()
    {
        $user_site_id = auth('sanctum')->user()->default_site_id;
        $shipments = Shipment::where('to_site_id', $user_site_id)->where('posted', true)->get();
        return response()->json([
            "success" => true,
            "data" => $shipments,
            "site_id" => $user_site_id
        ], 200);
    }

    public function postShipment(int $shipmentId)
    {
        try {
            $shipment = Shipment::where('id', $shipmentId)->with('specimen')->first();

            if (!$shipment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipment not found.',
                ], 404);
            }

            // check if shipment contains specimens
            if ($shipment->specimen->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipment cannot be posted because it does not contain any specimens.',
                ], 400);
            }

            if ($shipment->posted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipment has already been posted.',
                ], 400);
            }


            $shipment->posted = true;
            $shipment->shipped_by = auth('sanctum')->id();
            $shipment->date_shipped = now();
            $shipment->save();

            return response()->json([
                'success' => true,
                'message' => 'Shipment posted successfully.',
                'data' => $shipment,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while posting the shipment.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        try {
            $shipment = Shipment::with('created_by', 'received_by', 'shipped_by')->find($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error occurred while fetching shipment!",
                'error' => $e->getMessage()
            ], 500);
        }
        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => "Shipment not found!",
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $shipment,
        ], 200);
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
