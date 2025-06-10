<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\OfficeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


// ALL USERS ROUTES
// ___________________________________________

Route::get('/trips', [TripController::class, 'index']);
Route::get('/trip/{trip_id}', [TripController::class, 'tripById']);

Route::get('/offices', [OfficeController::class, 'index']);
Route::get('/office/{office_id}', [OfficeController::class, 'officeById']);


////todo : >>>>
// Route::get('/Users/createPilgrim', [TripController::class, 'tripById']);


// ADMIN AND OFFICE MANAGEMENT
// ____________________________________________


Route::middleware(['auth:sanctum', 'CheckUserRole:admin'])->group(function () {///// APIs for the office operations itself

    Route::post('/office/store', [OfficeController::class, 'store']);

    Route::post('/trip/store', [TripController::class, 'store']);

    Route::get('/office/{office}', [OfficeController::class, 'show']);

    Route::put('/office/{office}', [OfficeController::class, 'update']);

    Route::delete('/office/{office}', [OfficeController::class, 'destroy']);

    Route::post('/office/addEmployeeToOffice', [OfficeController::class, 'addEmployeeToOffice']);

});




Route::middleware(['auth:sanctum', 'CheckOfficeAndAdmin:admin'])->group(function () {///// APIs for the office operations itself

    

    Route::get('/trip/{trip}', [TripController::class, 'show']);

    Route::put('/trip/{trip}', [TripController::class, 'update']);

    Route::delete('/trip/{trip}', [TripController::class, 'destroy']);

});

