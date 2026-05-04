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

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()
    ]);
});

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
    Route::apiResource('worksheets', \App\Http\Controllers\Api\WorksheetController::class);
    Route::apiResource('sample-receipts', \App\Http\Controllers\Api\SampleReceptionController::class);

    Route::post('/participants/bulk', [StudyParticipantController::class, 'bulkStore']);
    Route::get('/test-types/parameters', [\App\Http\Controllers\Api\TestParameterController::class, 'testTypeParameters']);
    Route::post('/test-types/parameters', [\App\Http\Controllers\Api\TestParameterController::class, 'store']);

    // Route::post('/study-requirements', [\App\Http\Controllers\Api\StudyController::class, 'addTestRequirement']);
    // Route::get('/study-requirements', [\App\Http\Controllers\Api\StudyController::class, 'addTestRequirement']);

    // sample receipt report
    Route::get('/reports/sample-receipts', [\App\Http\Controllers\Api\SampleReceptionController::class, 'indexReport']);
    Route::get('/reports/sample-receipts/study', [\App\Http\Controllers\Api\SampleReceptionController::class, 'study_sample_report']);
    Route::get('/reports', [\App\Http\Controllers\Api\ReportsController::class, 'index']);

    Route::get('/study-collection-requirements', [\App\Http\Controllers\Api\StudyController::class, 'getStudyCollectionRequirements']);
    Route::get('/studies/{study}/forms', [\App\Http\Controllers\Api\StudyController::class, 'studyForms']);
    Route::post('/specimen/load/', [\App\Http\Controllers\Api\SpecimenController::class, 'loadSpecimen']);
    Route::get('/specimen/test-connection/', [\App\Http\Controllers\Api\SpecimenController::class, 'testConnection']);
    Route::get('/sites', [\App\Http\Controllers\Api\SiteController::class, 'index']);
    Route::get('/sites/{id}', [\App\Http\Controllers\Api\SiteController::class, 'show']);
    Route::get('/study-acc-forms-all', [\App\Http\Controllers\Api\StudyAccFormContoller::class, 'index']);


    // Route::get('/test-types', [TestTypeController::class, 'index']);


    // Admin only routes
    Route::middleware('admin')->group(function () {
        Route::apiResource('admin/sites', \App\Http\Controllers\Api\SiteController::class);
        Route::apiResource('studies-admin', \App\Http\Controllers\Api\StudyAdminController::class);
        Route::apiResource('test-types', App\Http\Controllers\Api\TestTypeController::class);
        Route::apiResource('storage-requirements', App\Http\Controllers\Api\StorageRequirementsController::class);
        Route::apiResource('users', UserController::class);
        // Route::apiResource('studies-admin', \App\Http\Controllers\Api\StudyAdminController::class);
        Route::apiResource('study-acc-forms', \App\Http\Controllers\Api\StudyAccFormContoller::class);
        Route::apiResource('sample-collection-requirements', \App\Http\Controllers\Api\SampleCollectionRequirementsController::class);
        Route::apiResource('test-requirements', App\Http\Controllers\Api\TestRequirementsController::class);
        Route::get('/reports/sample-reception', [\App\Http\Controllers\Api\SampleReceptionController::class, 'indexReport']);
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
