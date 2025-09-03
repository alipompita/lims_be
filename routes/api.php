<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SpecimenTypeControler;
use App\Http\Controllers\Api\StudyController;
use App\Http\Controllers\Api\StudyParticipantController;
use App\Http\Controllers\Api\TestTypeController;

use function Pest\Laravel\get;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // profile management
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'updatePassword']);

    // Lab functionality accessible by all authenticated users

    Route::apiResource('study-participants', \App\Http\Controllers\Api\StudyParticipantController::class);
    Route::apiResource('specimen-types', SpecimenTypeControler::class);
    Route::apiResource('studies', StudyController::class, ['except' => ['destroy', 'update', 'store']]);
    Route::apiResource('specimens', \App\Http\Controllers\Api\SpecimenController::class);

    Route::post('/participants/bulk', [StudyParticipantController::class, 'bulkStore']);

    // Admin only routes
    Route::middleware('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('studies-admin', StudyController::class);
        Route::apiResource('test-types', TestTypeController::class);
        Route::apiResource('test-parameters', \App\Http\Controllers\Api\TestParameterController::class);
    });
});

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});
