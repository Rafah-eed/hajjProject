<?php

    use App\Http\Controllers\HotelController;
    use App\Http\Controllers\PrayerController;
use App\Http\Controllers\RateController;
use App\Http\Middleware\CheckOfficeAndAdmin;
    use App\Http\Middleware\CheckUserRole;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\TripController;
    use App\Http\Controllers\OfficeController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\TransportationController;

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

    //General Routes

    Route::middleware('auth:sanctum')->group(function () {

    Route::get('/prayer/{prayer}', [PrayerController::class, 'getPrayerByID']);
    Route::get('/trips', [TripController::class, 'index']);
    Route::get('/trip/{trip_id}', [TripController::class, 'tripById']);
    Route::get('/offices', [OfficeController::class, 'index']); //no
    Route::get('/office/{office_id}', [OfficeController::class, 'officeById']);
    Route::get('/office/hotel/{hotel_id}', [HotelController::class, 'hotelById']);
    Route::get('/office/hotels/{hotel}/room-types', [HotelController::class, 'GetAllRoomTypesForHotel']);
    Route::get('/office/hotels/{hotel}/room-types/{room}', [HotelController::class, 'GetPriceRoomTypeForHotel']);
    Route::get('/prayers', [PrayerController::class, 'index']);
    Route::get('/office/transport/{transport_id}', [TransportationController::class, 'getTransportByID']);
    Route::get('/office/transport/seatsForTransport/{transport_id}', [TransportationController::class, 'getSeatsForTransport']);

        Route::get('/office/{user_id}/getOfficeId', [UserController::class, 'getOfficeId']);


        Route::post('/guide/rate/{guide_id}', [RateController::class, 'rateGuide']);
    Route::post('/trip/rate/{trip_id}', [RateController::class, 'rateTrip']);
    Route::get('/guide/getRate/{guide_id}', [RateController::class, 'getRateGuide']);
    Route::get('/trip/getRate/{trip_id}', [RateController::class, 'getRateTrip']);


    Route::get('/trip/getTripDetails/{trip_id}', [TripController::class, 'getTripDetails']);
    Route::get('/trip/getTripDetailsInteractive/{trip_id}', [TripController::class, 'getTripDetailsInteractive']);
    Route::post('/trip/reserveTrip', [TripController::class, 'reserveTrip']);

    Route::post('/Users/createPilgrim', [UserController::class, 'createPilgrim']);

    Route::get('/trip/{user_id}/getMyTrip', [TripController::class, 'getMyTrip']);

    Route::post('/office/addEmployeeToOffice', [OfficeController::class, 'addEmployeeToOffice']);

    });





    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
    });


        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
    });








    // ALL USERS ROUTES
    $roles = ['guide', 'admin', 'superAdmin'];
    $middlewareName = implode('|', $roles);

    Route::middleware(['auth:sanctum',  'CheckOfficeAndAdmin:$middlewareName'])->group(function ()  {

        Route::get('/getMyGuide', [UserController::class, 'getMyGuide']);

        Route::get('/pilgrim/{pilgrim_id}/getPilgrimProfile', [UserController::class, 'getPilgrimProfile']);

        Route::get('/trip/{trip_id}/pilgrims', [UserController::class, 'getPilgrimsByTripId']);

    });









    // ADMIN AND OFFICE MANAGEMENT
    $adminRoles = ['admin', 'superAdmin'];
    $middlewareNameAdmin = implode('|', $adminRoles);

    Route::middleware(['auth:sanctum', 'CheckOfficeAndAdmin:$middlewareNameAdmin'])->group(function () {
        // APIs for office operations
        Route::get('/office/{office_id}', [OfficeController::class, 'findOfficeById']);

        // Trip-related APIs
        Route::post('/{office_id}/trip/{trip_id}/guide/{user_id}/addGuideToTrip', [TripController::class, 'addGuideToTrip'])
            ->middleware([CheckOfficeAndAdmin::class,'RoleUsingId:guide']);
        Route::post('/{office_id}/trip/{trip_id}/guide/{user_id}/changeGuideForTrip', [TripController::class, 'changeGuide'])
            ->middleware([CheckOfficeAndAdmin::class,'RoleUsingId:guide']);

        Route::post('/{office_id}/trip/store', [TripController::class, 'store'])->middleware([CheckOfficeAndAdmin::class]);
        Route::put('/{office_id}/trip/{trip_id}', [TripController::class, 'update'])->middleware([CheckOfficeAndAdmin::class]);
        Route::delete('/{office_id}/trip/{trip_id}', [TripController::class, 'destroy'])->middleware([CheckOfficeAndAdmin::class]);

        // Hotel-related APIS
        Route::post('/{office_id}/hotel/store', [HotelController::class, 'store'])->middleware([CheckOfficeAndAdmin::class]);
        Route::put('/{office_id}/hotel/{hotel_id}', [HotelController::class, 'update'])->middleware([CheckOfficeAndAdmin::class]);
        Route::delete('/{office_id}/hotel/{hotel_id}', [HotelController::class, 'destroy'])->middleware([CheckOfficeAndAdmin::class]);
        Route::get('/{office_id}/hotels', [HotelController::class, 'index'])->middleware([CheckOfficeAndAdmin::class]);


        //Transport_related and seat APIS
        Route::post('/office/{office_id}/transport/store', [TransportationController::class, 'store'])->middleware([CheckOfficeAndAdmin::class]);
        Route::put('/office/{office_id}/transport/{transport_id}', [TransportationController::class, 'update'])->middleware([CheckOfficeAndAdmin::class]);
        Route::delete('/office/{office_id}/transport/{transport_id}', [TransportationController::class, 'destroy'])->middleware([CheckOfficeAndAdmin::class]);

        Route::post('/office/{office_id}/transport_seat/store', [TransportationController::class, 'storeSeat'])->middleware([CheckOfficeAndAdmin::class]);
        Route::put('/office/{office_id}/update/transport_seat/{transport_seat_id}', [TransportationController::class, 'updateSeat'])->middleware([CheckOfficeAndAdmin::class]);
        Route::delete('/office/{office_id}/transport_seat/{transport_seat_id}', [TransportationController::class, 'destroySeat'])->middleware([CheckOfficeAndAdmin::class]);


        Route::post('/trip/{office_id}/createTripAddsOn', [TripController::class, 'createTripAddsOn']);
        Route::post('/trip/{office_id}/updateTripAddsOn/{trip_id}', [TripController::class, 'updateTripAddsOn']);

    });







    // SUPER ADMIN MANAGEMENT
    Route::middleware(['auth:sanctum', 'CheckOfficeAndAdmin:superAdmin'])->group(function () {
        // APIs for prayer operations
        Route::post('/prayer/store', [PrayerController::class, 'store']);
        Route::put('/prayer/{prayer_id}', [PrayerController::class, 'update']);
        Route::delete('/prayer/{prayer}', [PrayerController::class, 'destroy']);

        // APIs for office operations
        Route::post('/office/store', [OfficeController::class, 'store']);
        Route::put('/office/{office_id}', [OfficeController::class, 'update']);
        Route::delete('/office/{office}', [OfficeController::class, 'destroy']);

        //APIS for transportation and seats
        Route::get('/transports', [TransportationController::class, 'index']);

        Route::get('/getAllPilgrims', [UserController::class, 'getAllPilgrims']);

    });
