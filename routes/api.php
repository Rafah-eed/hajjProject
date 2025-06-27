    <?php

    use App\Http\Controllers\HotelController;
    use App\Http\Controllers\PrayerController;
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

    //General Routes
    Route::get('/prayer/{prayer}', [PrayerController::class, 'getPrayerByID']);
    Route::get('/trips', [TripController::class, 'index']);
    Route::get('/trip/{trip_id}', [TripController::class, 'tripById']);
    Route::get('/offices', [OfficeController::class, 'index']); //no
    Route::get('/office/{office_id}', [OfficeController::class, 'officeById']);
    Route::get('/office/hotel/{hotel_id}', [HotelController::class, 'hotelById']);
    Route::get('/office/hotels/{hotel}/room-types', [HotelController::class, 'GetAllRoomTypesForHotel']);
    Route::get('/office/hotels/{hotel}/room-types/{room}', [HotelController::class, 'GetPriceRoomTypeForHotel']);
    Route::get('/prayers', [PrayerController::class, 'index']);






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

    Route::middleware(['auth:sanctum', $middlewareName])->group(function () {
        Route::get('/getMyGuide', [UserController::class, 'getMyGuide']);

    });











    // ADMIN AND OFFICE MANAGEMENT
    $adminRoles = ['admin', 'superAdmin'];
    $middlewareNameAdmin = implode('|', $adminRoles);

    Route::middleware(['auth:sanctum', 'CheckOfficeAndAdmin:$middlewareNameAdmin'])->group(function () {
        // APIs for office operations
        Route::get('/office/{office_id}', [OfficeController::class, 'findOfficeById']);
        Route::post('/office/{office_id}/addEmployeeToOffice', [OfficeController::class, 'addEmployeeToOffice']);

        // Trip-related APIs
        Route::post('/{office_id}/trip/store', [TripController::class, 'store'])->middleware([CheckOfficeAndAdmin::class]);
        Route::put('/{office_id}/trip/{trip_id}', [TripController::class, 'update'])->middleware([CheckOfficeAndAdmin::class]);
        Route::delete('/{office_id}/trip/{trip_id}', [TripController::class, 'destroy'])->middleware([CheckOfficeAndAdmin::class]);

        // Hotel-related APIS
        Route::post('/{office_id}/hotel/store', [HotelController::class, 'store'])->middleware([CheckOfficeAndAdmin::class]);;
        Route::put('/{office_id}/hotel/{hotel_id}', [HotelController::class, 'update'])->middleware([CheckOfficeAndAdmin::class]);
        Route::delete('/{office_id}/hotel/{hotel_id}', [HotelController::class, 'destroy'])->middleware([CheckOfficeAndAdmin::class]);
        Route::get('/{office_id}/hotels', [HotelController::class, 'index'])->middleware([CheckOfficeAndAdmin::class]);



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

    });