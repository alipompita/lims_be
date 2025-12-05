<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudyTestRequirement;
use App\Models\Study;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TestRequirementsController extends Controller
{

    public function index()
    {

        $requirements = StudyTestRequirement::with('study', 'spectype')->get();
        return response()->json([
            'success' => true,
            'data' => $requirements
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Not implemented yet',
        ], 501);
    }
}
