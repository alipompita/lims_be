<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sites = Site::all();

        return response()->json([
            'success' => true,
            'data' => $sites,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $site = Site::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Site created successfully',
            'data' => $site,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $site = Site::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $site,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
