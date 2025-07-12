<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripValidationRequest;
use App\Http\Resources\TripDetailsResource;
use App\Models\Payment;
use App\Models\Room;
use App\Models\TransportSeat;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


use App\Models\HotelTrip;
use App\Models\TransportTrip;
use App\Models\Guide;
use App\Models\Hotel;
use App\Models\Transport;


class TripController extends BaseController
{

    public function index(): JsonResponse
    {
        $trip = Trip::all();

        if ( is_null($trip))
            return $this->sendResponse(false,  "No data available" ,204);

        return $this->sendResponse($trip, "trips has been retrieved");

    }

    public function tripById($trip_id): JsonResponse
    {
        $trip = Trip::find($trip_id);

        if ( is_null($trip))
            return $this->sendResponse(false,  "No Trip with sent  ID" ,204);

        return $this->sendResponse($trip, "trip has been retrieved");

    }

    public function store(TripValidationRequest $request): JsonResponse
    {
        $validatedData = $request->validated();


        // Generate a random trip code
        $randomCode = Str::padLeft(rand(100000, 999999), 5, '0');

        // Create the trip with the generated code
        $trip = Trip::create(array_merge($validatedData, ['trip_code' => $randomCode]));


        if ( is_null($trip))

            return $this->sendResponse(false,  "error in create" ,204);

        return $this->sendResponse($trip, "trip has been created");

    }

    public function update(TripValidationRequest $request, $trip_id): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $trip = Trip::findOrFail($trip_id);

            $trip->update($validatedData);
        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
        }

        return $this->sendResponse($trip, "trip has been updated");
    }

    public function destroy(int $trip_id): JsonResponse
    {
        try {
            $trip = Trip::findOrFail($trip_id);

            $trip->delete();
        }
        catch (\Exception $e) {
            Log::error('Error deleting trip: ' . $e->getMessage());
        }

        return $this->sendResponse("delete", "trip has been deleted");
    }

    //TODO : CHECK TRIP STATE, Trip rating, userRATING

    public function addGuideToTrip(Request $request, int $trip_id, int $user_id): JsonResponse
    {
        try {

            $user = User::findOrFail($user_id);
            $trip = Trip::findOrFail($trip_id);

            if ( !$user || !$trip){
                return $this->sendResponse(false, "Error user or trip not found " );
            }

            $office_id = $request->route('office_id');
            // Create new guide record
            DB::table('guides')->insert([
                'user_id' => $user_id,
                'office_id' => $office_id,
                'trip_id' => $trip_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->sendResponse(true, "Guide added successfully");
        }
        catch (\Exception $e) {
            Log::error('Error adding guide to trip: ' . $e->getMessage());
            return $this->sendResponse(false, "Error adding guide: " . $e->getMessage());
        }
    }


    public function changeGuide(Request $request, int $trip_id, int $user_id): JsonResponse
    {
        try {
            // Retrieve office_id from route
            $office_id = $request->route('office_id');

            // Find user and trip, throw error if not found
            $user = User::findOrFail($user_id);
            $trip = Trip::findOrFail($trip_id);

            if (!$user || !$trip) {
                return $this->sendResponse(false, "Error user or trip not found");
            }

            // Check if a guide record already exists for this trip and user
            $existingGuide = DB::table('guides')
                ->where('office_id', $office_id)
                ->where('trip_id', $trip_id)
                ->first();

            if ($existingGuide) {
                // If found, update the office_id and updated_at timestamp
                DB::table('guides')
                    ->where('id', $existingGuide->id)
                    ->update([
                        'user_id' => $user_id,
                        'updated_at' => now(),
                    ]);
                $message = "Guide updated successfully";
            } else {
                // If not found, insert a new guide record
                DB::table('guides')->insert([
                    'user_id' => $user_id,
                    'office_id' => $office_id,
                    'trip_id' => $trip_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $message = "Guide added successfully, it did not exist before";
            }

            return $this->sendResponse(true, $message);
        } catch (\Exception $e) {
            Log::error('Error changing guide for trip: ' . $e->getMessage());
            return $this->sendResponse(false, "Error changing guide: " . $e->getMessage());
        }
    }


    public function getTripDetails($trip_id): JsonResponse
    {
        $trip = Trip::with([
            'hotel_trips.hotel',
            'transport_trips.transport.transport_seats',
            'guides.user'
        ])->find($trip_id);

        if (!$trip) {
            return $this->sendError(false, "trip not found", 404);
        }
        return $this->sendResponse(true, [
            'data' => new TripDetailsResource($trip)
        ]);

    }

    public function getTripDetailsInteractive($trip_id): JsonResponse
    {
        $trip = Trip::with([
            'hotel_trips.hotel',
            'transport_trips.transport.transport_seats',
            'guides.user'
        ])->find($trip_id);

        if (!$trip) {
            return $this->sendError(true, "trip not found");
        }

        return $this->sendResponse(true, $trip);
    }


    public function reserveTrip(Request $request): JsonResponse
    {

        $user = Auth::user();
        $user_id = $user->id;

    $validatedData = $request->validate([
        'trip_id' => 'required|exists:trips,id',
        'transport_seat_id' => 'required|exists:transport_seats,id',
        'room_id' => 'required|exists:rooms,id'
    ]);

    $trip = Trip::findOrFail ($validatedData['trip_id']);
    $transportSeat = TransportSeat::findOrFail ($validatedData['transport_seat_id']);
    $room = Room::findOrFail ($validatedData['room_id']);


    $total_price = $trip->price
                    + $transportSeat->price
                    + $room->price;


    // Check availability
    if (!$trip->checkAvailability($trip->enrollNum)) {
        return $this->sendResponse(false, "Not enough spots available for this trip.", 400);
    }

    // Create payment record
    $payment = Payment::create([
        'user_id' => $user_id,
        'trip_id' => $validatedData['trip_id'],
        'transport_seat_id' => $validatedData['transport_seat_id'],
        'room_id' => $validatedData['room_id'],
        'total_price' => $total_price,
        'status' => 'await',  // default status
    ]);

    // Respond with success or redirect
    return response()->json([
        'success' => true,
        'message' => 'Reservation saved successfully.',
        'invoice' => $payment
    ]);
}


public function getMyTrip($user_id): JsonResponse
{
    try {
        // First, let's find all payment records for the user
        $payments = Payment::where('user_id', $user_id)->get();

        if ($payments->isEmpty()) {
            return response()->json(['message' => 'No payments found for this user'], 404);
        }

        // Now, let's prepare the data we want to return
        $result = [];
        foreach ($payments as $payment) {
            $result[] = [
                'id' => $payment->id,
                'trip_id' => $payment->trip_id,
                'transport_seat_id' => $payment->transport_seat_id,
                'room_id' => $payment->room_id,
                'total_price' => $payment->total_price,
                'status' => $payment->status,
                'created_at' => $payment->created_at,
            ];

            // Fetch additional information for each payment
            $trip = Trip::find($payment->trip_id);
            if ($trip) {
                $result[$payment->id]['trip_details'] = [
                    'name' => $trip->regiment_name,
                    'type' => $trip->type,
                    'start_date' => $trip->start_date,
                    'end_date' => $trip->end_date,
                ];
            }

            $transportSeat = TransportSeat::find($payment->transport_seat_id);
            if ($transportSeat) {
                $result[$payment->id]['transport_details'] = [
                    'seat' => $transportSeat->seat,
                    'price' => $transportSeat->price,
                ];
            }

            $room = Room::find($payment->room_id);
            if ($room) {
                $result[$payment->id]['room_details'] = [
                    'room_type' => $room->name,
                    'price' => $room->capacity,
                ];
            }
        }

        return response()->json(['data' => $result]);
    } catch (\Exception $e) {
        Log::error('Error fetching user trip details: ' . $e->getMessage());
        return response()->json(['message' => 'An error occurred while fetching trip details'], 500);
    }
}


public function createTripAddsOn(Request $request): JsonResponse
{
    try {
        // Validate the request data
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'regiment_name' => 'required|string|max:255',
            'days_num_makkah' => 'required|integer|min:1',
            'days_num_madinah' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean',
            'office_id' => 'required|exists:offices,id',
            'numOfReservations' =>'required|integer|min:1',
            'hotel_ids' => 'required|array',
            'transport_ids' => 'required|array',
            'guide_id' => 'required|exists:guides,id',
        ]);

        // Create the trip
        $trip = Trip::create([
            'type' => $validatedData['type'],
            'regiment_name' => $validatedData['regiment_name'],
            'days_num_makkah' => $validatedData['days_num_makkah'],
            'days_num_madinah' => $validatedData['days_num_madinah'],
            'price' => $validatedData['price'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'office_id' => $validatedData['office_id'],
            'is_active' =>  $validatedData['is_active'],
            'numOfReservations' => $validatedData['numOfReservations'],
            'enrollNum' => 0,
            'trip_code' => Str::padLeft(rand(100000, 999999), 5, '0'),
        ]);

        // Add hotels to the trip
        foreach ($validatedData['hotel_ids'] as $hotelId) {
            HotelTrip::create([
                'trip_id' => $trip->id,
                'hotel_id' => $hotelId,
                'office_id' => $validatedData['office_id'],

            ]);
        }

        // Add transports to the trip
        foreach ($validatedData['transport_ids'] as $transportId) {
            TransportTrip::create([
                'trip_id' => $trip->id,
                'transport_id' => $transportId,
                'office_id' => $validatedData['office_id'],
            ]);
        }

        Guide::create([
            'user_id' => $validatedData['guide_id'], // fix here
            'office_id' => $validatedData['office_id'],
            'trip_id' => $trip->id,
        ]);

// Fetch the guide
        $guide = Guide::where('trip_id', $trip->id)->first();

        $guideData = null;
        if ($guide) {
            $user = User::find($guide->user_id);
            $fullName = $user ? ($user->first_name . ' ' . $user->last_name) : '';
            $guideData = [
                'id' => $guide->user_id,
                'name' => $fullName ?? '',
            ];
        }

// Prepare the response data
        $responseData = [
            'message' => 'Trip created successfully',
            'trip' => [
                'id' => $trip->id,
                'type' => $trip->type,
                'regiment_name' => $trip->regiment_name,
                'days_num_makkah' => $trip->days_num_makkah,
                'days_num_madinah' => $trip->days_num_madinah,
                'price' => $trip->price,
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date,
                'office_id' => $trip->office_id,
                'is_active' => $trip->is_active,
                'numOfReservations' => $trip->numOfReservations,
                'enrollNum' => $trip->enrollNum,
                'trip_code' => $trip->trip_code,
            ],
            'hotels' => HotelTrip::where('trip_id', $trip->id)
                ->get()
                ->map(function($ht) {
                    $hotel = Hotel::find($ht->hotel_id);
                    return [
                        'id' => $ht->hotel_id,
                        'name' => optional($hotel)->hotel_name ?? '',
                        'rate' => optional($hotel)->rate ?? '',
                        'address' => optional($hotel)->address ?? '',
                    ];
                }),
            'transports' => TransportTrip::where('trip_id', $trip->id)
                ->get()
                ->map(function($tt) {
                    $transport = Transport::find($tt->transport_id);
                    return [
                        'id' => $tt->transport_id,
                        'transport_type' => optional($transport)->transport_type ?? '',
                        'company_name' => optional($transport)->company_name ?? '',
                        'description' => optional($transport)->description ?? '',
                    ];
                }),
            'guide' => $guideData, // add guide info here
        ];

        return response()->json($responseData, 201);

    } catch (\Exception $e) {
        Log::error('Error creating trip with add-ons: ' . $e->getMessage());
        return response()->json(['message' => 'An error occurred while creating the trip'], 500);
    }
}

public function updateTripAddsOn(Request $request, $office_id, $trip_id): JsonResponse
{
    Log::info("Received trip_id: " . $trip_id);
    try {
        // Validate request data
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'regiment_name' => 'required|string|max:255',
            'days_num_makkah' => 'required|integer|min:1',
            'days_num_madinah' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' =>'required|boolean',
            'office_id' => 'required|exists:offices,id',
            'numOfReservations' => 'required|integer|min:1',
            'hotel_ids' => 'required|array',
            'transport_ids' => 'required|array',
            'guide_id' => 'required|exists:guides,id',
        ]);

        // Find existing trip
        $trip = Trip::findOrFail($trip_id);
        Log::info("Updating trip with ID: " . $trip_id);
        // Update trip details
        $trip->update([
            'type' => $validatedData['type'],
            'regiment_name' => $validatedData['regiment_name'],
            'days_num_makkah' => $validatedData['days_num_makkah'],
            'days_num_madinah' => $validatedData['days_num_madinah'],
            'price' => $validatedData['price'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'is_active' => $validatedData['is_active'],
            'office_id' => $validatedData['office_id'],
            'numOfReservations' => $validatedData['numOfReservations'],
        ]);

        // Update hotels: Remove old, add new
        HotelTrip::where('trip_id', $trip->id)->delete();
        foreach ($validatedData['hotel_ids'] as $hotelId) {
            HotelTrip::create([
                'trip_id' => $trip->id,
                'hotel_id' => $hotelId,
                'office_id' => $validatedData['office_id'],
            ]);
        }

        // Update transports: Remove old, add new
        TransportTrip::where('trip_id', $trip->id)->delete();
        foreach ($validatedData['transport_ids'] as $transportId) {
            TransportTrip::create([
                'trip_id' => $trip->id,
                'transport_id' => $transportId,
                'office_id' => $validatedData['office_id'],
            ]);
        }

        // Update guide
        // You may want to update or reassign guide, depending on logic
        Guide::updateOrCreate(
            ['trip_id' => $trip->id],
            [
                'user_id' => $validatedData['guide_id'],
                'office_id' => $validatedData['office_id'],
            ]
        );
        $guide = Guide::where('trip_id', $trip->id)->first();

        $guideData = null;
        if ($guide) {
            $user = User::find($guide->user_id);
            $fullName = $user ? ($user->first_name . ' ' . $user->last_name) : '';
            $guideData = [
                'id' => $guide->user_id,
                'name' => $fullName ?? '',
            ];
        }
        // Prepare response similar to create
        $responseData = [
            'message' => 'Trip updated successfully',
            'trip' => [
                'id' => $trip->id,
                'type' => $trip->type,
                'regiment_name' => $trip->regiment_name,
                'days_num_makkah' => $trip->days_num_makkah,
                'days_num_madinah' => $trip->days_num_madinah,
                'price' => $trip->price,
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date,
                'office_id' => $trip->office_id,
                'is_active' => $trip->is_active,
                'numOfReservations' => $trip->numOfReservations,
                'enrollNum' => $trip->enrollNum,
                'trip_code' => $trip->trip_code,
            ],
            'hotels' => HotelTrip::where('trip_id', $trip->id)
                ->get()
                ->map(function($ht) {
                    $hotel = Hotel::find($ht->hotel_id);
                    return [
                        'id' => $ht->hotel_id,
                        'name' => optional($hotel)->hotel_name ?? '',
                        'rate' => optional($hotel)->rate ?? '',
                        'address' => optional($hotel)->address ?? '',
                    ];
                }),
            'transports' => TransportTrip::where('trip_id', $trip->id)
                ->get()
                ->map(function($tt) {
                    $transport = Transport::find($tt->transport_id);
                    return [
                        'id' => $tt->transport_id,
                        'transport_type' => optional($transport)->transport_type ?? '',
                        'company_name' => optional($transport)->company_name ?? '',
                        'description' => optional($transport)->description ?? '',
                    ];
                }),
            'guide' => $guideData, // add guide info here
        ];

        return response()->json($responseData, 201);
    } catch (\Exception $e) {
        Log::error('Error updating trip: ' . $e->getMessage());
        return response()->json(['message' => 'An error occurred while updating the trip'], 500);
    }
}

}
