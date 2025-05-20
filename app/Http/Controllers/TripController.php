<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;


class TripController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
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


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'type' => 'required|in:umrah,hajjQ,hajjT,hajjI',
            'regiment_name' => 'required|string|max:255',
            'days_num_makkah' => 'required|int|max:255',
            'days_num_madinah' => 'required|int|max:255',
            'price' => 'required|decimal|max:255',
            'start_date' => 'required',
            'is_active'  => 'required|boolean',
        ]);

        $trip = Trip::create([
            'type' => $request->type,
            'regiment_name' => $request->regiment_name,
            'days_num_makkah' => $request->days_num_makkah,
            'days_num_madinah' => $request->days_num_madinah,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'is_active' => $request->is_active,

        ]);

        if ( is_null($trip))
            return $this->sendResponse(false,  "error in create" ,204);

        return $this->sendResponse($trip, "trip has been created");

    }

    public function update(Request $request, Trip $trip): JsonResponse
    {

        $fields = $request->validate([
            'type' => 'required|in:umrah,hajjQ,hajjT,hajjI',
            'regiment_name' => 'required|string|max:255',
            'days_num_makkah' => 'required|int|max:255',
            'days_num_madinah' => 'required|int|max:255',
            'price' => 'required|decimal|max:255',
            'start_date' => 'required',
            'is_active'  => 'required|boolean',
        ]);


        try {
            $trip->update($fields);
        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
            // Handle the error (e.g., return an error response)
        }

        // $trip->station->attach($request->station_id);

        return $this->sendResponse($trip, "trip has been updated");
    }


    public function destroy(int $trip_id): JsonResponse
    {
        $trip = Trip::findOrFail($trip_id);
        $trip->delete();

        return self::getResponse(true, "Trip and its related active records have been deleted", null, 200);
    }



}
