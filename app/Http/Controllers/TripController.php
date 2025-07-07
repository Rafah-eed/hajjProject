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

}
