<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\DriverController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and are assigned to the "api"
| middleware group.
|
*/


Route::post('/login', 'AuthController@login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::resource('vendors', 'VendorController');
    Route::resource('drivers', 'DriverController');

});

// Route::middleware(['auth:sanctum', 'role:vendor'])->group(function () {
//     Route::get('/vendor/tasks', [ContractorController::class, 'tasks']);

// });


// Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
//     Route::get('/driver/orders', [DriverController::class, 'orders']);

// });
