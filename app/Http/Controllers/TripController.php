<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripValidationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;


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


        $trip = Trip::create([$validatedData]);

        if ( is_null($trip))

            return $this->sendResponse(false,  "error in create" ,204);

        return $this->sendResponse($trip, "trip has been created");

    }

    public function update(TripValidationRequest $request, Trip $trip): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $trip->update($validatedData);
        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
        }

        return $this->sendResponse($trip, "trip has been updated");
    }

    public function destroy(int $trip_id): JsonResponse
    {
        $trip = Trip::findOrFail($trip_id);

        $trip->delete();

        return $this->sendResponse($trip, "trip has been deleted");}



}
