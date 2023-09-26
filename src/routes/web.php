<?php
use Illuminate\Support\Facades\Route;
use Fieroo\Stands\Controllers\StandController;
use Fieroo\Stands\Controllers\StandsTypesController;

Route::group(['prefix' => 'admin', 'middleware' => ['web','auth']], function() {
    Route::resource('/stands-types', StandsTypesController::class);
    Route::group(['prefix' => 'stands'], function() {
        Route::post('/getSelectList', [StandController::class, 'getSelectList']);
        Route::post('/getData', [StandController::class, 'getData']);
    });
});