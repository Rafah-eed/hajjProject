<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripValidationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Trip;
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

}
