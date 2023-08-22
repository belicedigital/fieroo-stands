<?php
use Illuminate\Support\Facades\Route;
use Fieroo\Stands\Controllers\StandController;
use Fieroo\Stands\Controllers\StandsTypesController;

Route::group(['prefix' => 'admin', 'middleware' => ['web','auth']], function() {
    Route::resource('/stands-types', StandsTypesController::class);
    Route::group(['prefix' => 'stands'], function() {
        Route::get('/', [StandController::class, 'index']);
        Route::post('/getSelectList', [StandController::class, 'getSelectList']);
        Route::post('/getFurnishingsList', [StandController::class, 'getFurnishingsList']);
        Route::get('/{code_module}/{stand_type_id}/furnishings', [StandController::class, 'indexFurnishings']);
        Route::get('/{code_module}/{stand_type_id}/furnishings/show', [StandController::class, 'showFurnishings']);
        Route::post('/getData', [StandController::class, 'getData']);
        Route::post('/confirm', [StandController::class, 'confirm']);
    });
});