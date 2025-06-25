<?php

use App\Http\Middleware\CheckOfficeAndAdmin;
use App\Http\Middleware\CheckUserRole;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\UserController;
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
// Route::get('/Users/tripCode', [TripController::class, 'tripCode']);



// ADMIN AND OFFICE MANAGEMENT
// ____________________________________________

Route::middleware(['auth:sanctum',  'combined.check.user.or.office.admin'])->group(function () {///// APIs for the office operations itself

    Route::post('/office/store', [OfficeController::class, 'store']);

    Route::get('/office/{office}', [OfficeController::class, 'show']);

    Route::put('/office/{office_id}', [OfficeController::class, 'update']);

    Route::delete('/office/{office}', [OfficeController::class, 'destroy']);

    Route::post('/office/addEmployeeToOffice', [OfficeController::class, 'addEmployeeToOffice']);


    Route::post('/trip/store', [TripController::class, 'store']) -> middleware([CheckOfficeAndAdmin::class]);

    Route::get('/trip/{trip}', [TripController::class, 'show']);

    Route::put('/trip/{trip}', [TripController::class, 'update']) -> middleware([CheckOfficeAndAdmin::class]);

    Route::delete('/trip/{trip}', [TripController::class, 'destroy']) -> middleware([CheckOfficeAndAdmin::class]);


});


///'user', 'guide', 'admin', 'superAdmin'

Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::get('/getMyGuide', [UserController::class, 'getMyGuide']);

});

Route::middleware('auth:sanctum')->group(function () {
    
    $roles = ['guide', 'admin', 'superAdmin'];
    $middlewareName = implode('|', $roles);

    Route::middleware("combined:$middlewareName")->group(function () {
    
        Route::get('/getAllPilgrims', [UserController::class, 'getAllPilgrims']);
        
        Route::get('/trip/{trip_id}/pilgrims', [UserController::class, 'getPilgrimsByTripId'])  -> middleware([CheckOfficeAndAdmin::class]) ;
        
        Route::post('/trip/addGuideToTrip', [UserController::class, 'addGuideToTrip']) -> middleware([CheckOfficeAndAdmin::class]) ;

        Route::post('/Users/createGuide', [UserController::class, 'createGuide']) ;
        
        Route::post('/Users/createPilgrim', [UserController::class, 'createPilgrim']) -> middleware([CheckOfficeAndAdmin::class]) ;
        
        Route::get('/getAllOfficeEmployees/{trip_id}', [UserController::class, 'getAllOfficeEmployees']) -> middleware([CheckOfficeAndAdmin::class]) ;

        
    
    
    
    
    });
});