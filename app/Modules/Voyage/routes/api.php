<?php

use App\Modules\Voyage\Http\Controllers\VoyageController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => ''
 
], function ($router) {
    Route::get('/getAllVoyages', [VoyageController::class, 'getAllVoyages']);
    Route::get('/sendShiftReport', [VoyageController::class, 'sendShiftReport']);
});


Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'api/voyages'
 
], function ($router) {
    Route::get('/allVoyages/{date}', [VoyageController::class, 'allVoyages']);
   
    //Route::post('/create', [VoyageController::class, 'create']);
   // Route::post('/update', [VoyageController::class, 'update']);
    Route::get('/', [VoyageController::class, 'index']);
    Route::get('/archivedIndex', [VoyageController::class, 'archivedIndex']);
    Route::get('/showAll', [VoyageController::class, 'indexAll']);
    Route::get('/showAllArchivedIndexAll', [VoyageController::class, 'archivedIndexAll']);
    Route::get('/{id}', [VoyageController::class, 'get']);
    Route::get('/getActionHistory/{vessel_id}', [VoyageController::class, 'getActionHistory']);
    Route::post('/delete', [VoyageController::class, 'delete']);
    Route::post('/archive', [VoyageController::class, 'archive']);
    Route::post('/unarchive', [VoyageController::class, 'unarchive']);
    Route::post('/undoAnctionFunction', [VoyageController::class, 'undoAnctionFunction']);
    Route::post('/vesselAllActionsDetails', [VoyageController::class, 'vesselAllActionsDetails']);
    Route::post('/CraneVoyageDelete', [VoyageController::class, 'CraneVoyageDelete']);
    Route::post('/saveOrUpdateVoyage', [VoyageController::class, 'saveOrUpdateVoyage']);
    Route::post('/deleteOtherDelay', [VoyageController::class, 'deleteOtherDelay']);
    
});

