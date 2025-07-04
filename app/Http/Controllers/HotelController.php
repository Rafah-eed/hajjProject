<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HotelController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $hotels = Hotel::all();

        if ( is_null($hotels))
            return $this->sendResponse(false,  "No data available" ,204);

        return $this->sendResponse($hotels, "hotels has been retrieved");

    }

    public function hotelById($hotel_id): JsonResponse
    {
        $hotel = Hotel::find($hotel_id);

        if ( is_null($hotel))
            return $this->sendResponse(false,  "No hotel with sent  ID" ,204);

        return $this->sendResponse($hotel, "hotel has been retrieved");

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $validatedData = $request->validate([
                'office_id' => 'required|exists:offices,id',
                'hotel_name' => 'required|string|max:255',
                'rate' => 'required|numeric|min:0|max:99999999.99',
                'address' => 'required|string|max:255',
            ]);

            $hotel = new Hotel();
            $hotel->fill($validatedData);
            $hotel->save();

            return $this->sendResponse($hotel, "Hotel has been created successfully");
        } catch (\Exception $e) {
            Log::error('Error creating hotel: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while creating the hotel", 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $hotel = $this->findHotelById($id);
            if (!$hotel) {
                return $this->sendResponse(false, "Hotel not found.", 404);
            }

            // Handle partial updates
            $updates = [];
            if ($request['hotel_name']) {
                $updates['hotel_name'] = $request['hotel_name'];
            }
            if ($request['rate']) {
                $updates['rate'] = $request['rate'];
            }
            if ($request['address']) {
                $updates['address'] = $request['address'];
            }
            if ($request['office_id']) {
                $updates['office_id'] = $request['office_id'];
            }


            if (!empty($updates)) {
                $hotel->update($updates);
            } else {
                return $this->sendResponse($hotel, "No changes were made to the hotel.");
            }

            return $this->sendResponse($hotel, "hotel has been updated successfully");
        } catch (\Exception $e) {
            Log::error('Error updating hotel: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while updating the hotel", 500);
        }
    }

    private function findHotelById($id)
    {
        return Hotel::findOrFail($id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $hotel = Hotel::findOrFail($id);

        $hotel->delete();

        return $this->sendResponse($hotel, "hotel has been deleted");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function addRoomTypeToHotel(Request $request, $hotel_id): JsonResponse
    {
        try {

            $hotel = Hotel::findOrFail($hotel_id);

        $validatedData = $request->input();

            if ($request->isNotFilled(['room_type', 'price', 'hotel_id'])) {
                return $this->sendResponse(false, "room_type and price and hotel_id is required.", 400);

            }

            $room = new Room();
            $room->fill($validatedData);
            $room->save();

            return $this->sendResponse($room, "room has been added successfully");
        } catch (\Exception $e) {
            Log::error('Error adding room: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while creating the room", 500);
        }

   }


    public function GetAllRoomTypesForHotel($hotel_id): JsonResponse
    {
        try {
            $rooms = Room::where('hotel_id', $hotel_id)->get();

            if (is_null($rooms)) {
                return $this->sendResponse(false, "No rooms available for this hotel", 404);
            }

            return $this->sendResponse($rooms, "Rooms have been retrieved successfully");
        } catch (\Exception $e) {
            Log::error('Error retrieving rooms: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while retrieving rooms", 500);
        }
    }

    public function GetPriceRoomTypeForHotel($room_id): JsonResponse
    {
        try {
            $room = Room::findOrFail($room_id);

            if (is_null($room)) {
                return $this->sendResponse(false, "Room not found", 404);
            }

            return $this->sendResponse($room, "Room details have been retrieved successfully");
        } catch (\Exception $e) {
            Log::error('Error retrieving room: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while retrieving room details", 500);
        }
    }


}
