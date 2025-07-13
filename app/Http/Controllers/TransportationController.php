<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Transport;
use App\Models\TransportSeat;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class TransportationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $transports = Transport::all();

        if ( is_null($transports))
            return $this->sendResponse(false,  "No data available" );

        return $this->sendResponse($transports, "Transports has been retrieved");

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
            DB::enableQueryLog();

            $validatedData = $request->validate([
                'office_id' => 'required|exists:offices,id',
                'company_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'transport_type' => 'required|string|max:50',
            ]);

            $transport = new Transport();
            $transport->fill($validatedData);
            Log::info("Before saving:", ['prayer' => $transport]);

            $transport->save();

            Log::info("After saving:", ['result' => $transport]);

            return $this->sendResponse($transport, "transport has been added successfully");
        } catch (Exception $e) {
            Log::error('Error adding transport: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while adding the transport");
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            DB::enableQueryLog();

            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'transport_type' => 'required|string|max:50',
            ]);

            // Compare office_id from request with one in URL
            $urlOfficeId = $this->getOfficeIdFromUrl($id);

            $transport = Transport::findOrFail($id);

            if ($transport->office_id != $urlOfficeId){
                return $this->sendResponse(false, "You are not authorized to update the seat");
            }

            Log::info("Before saving:", ['transport' => $transport]);

            $transport->update([
                'office_id' => $urlOfficeId,
                'company_name' => $validatedData['company_name'] ?? null,
                'description' => $validatedData['description'] ?? null,
                'transport_type' => $validatedData['transport_type'] ?? null,
            ]);

            Log::info("After saving:", ['result' => $transport]);

            return $this->sendResponse($transport, "transport updated successfully");
        } catch (Exception $e) {
            Log::error('Error updating transport: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while updating the transport");
        }
    }

    private function getOfficeIdFromUrl(int $transport_id): ?int
    {
        // Assuming the route parameter is named 'office_id'
        $routeParams = app()->router->current()->parameters();

        if (!isset($routeParams['office_id'])) {
            throw new InvalidArgumentException("Office ID not found in URL parameters.");
        }

        return intval($routeParams['office_id']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $transport = Transport::findOrFail($id);

        $transport->delete();

        return $this->sendResponse($transport, "transport has been deleted");

    }

    public function getTransportByID($transport_id): JsonResponse
    {
        $transport = Transport::findOrFail($transport_id);

        if ( is_null($transport))
            return $this->sendResponse(false,  "No data available" ,204);

        return $this->sendResponse($transport, "transport has been retrieved");

    }

// /**
//      * Display a listing of the resource.
//      *
//      * @return JsonResponse
//      */
//     public function indexSeat(): JsonResponse
//     {
//         $seats = Transport_Seat::all();

//         if ( is_null($seats))
//             return $this->sendResponse(false,  "No data available" ,204);

//         return $this->sendResponse($seats, "seats has been retrieved");

//     }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeSeat(Request $request): JsonResponse
    {
        try {
            DB::enableQueryLog();

            $validatedData = $request->validate([
                'transport_id' => 'required|exists:transports,id',
                'seat' => 'required',
                'price' => 'required',
            ]);

            $seat = new TransportSeat();
            $seat->fill($validatedData);
            Log::info("Before saving:", ['prayer' => $seat]);

            $seat->save();

            Log::info("After saving:", ['result' => $seat]);

            return $this->sendResponse($seat, "seat has been added successfully");
        } catch (Exception $e) {
            Log::error('Error adding seat: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while adding the seat", 500);
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $transport_seat_id
     * @return JsonResponse
     */
    // public function updateSeat(Request $request, int $transport_seat_id): JsonResponse
    // {
    //     try {
    //         DB::enableQueryLog();

    //         $validatedData = $request->validate([
    //             'transport_id' => 'required|exists:transports,id',
    //             'seat' => 'required',
    //             'price' => 'required',
    //         ]);

    //         $seat = TransportSeat::findOrFail($transport_seat_id);

    //         Log::info("Before saving:", ['seat' => $seat]);

    //         $seat->update([
    //             'transport_id' => $validatedData['transport_id'],
    //             'seat' => $validatedData['seat'],
    //             'price' => $validatedData['price'],
    //         ]);

    //         Log::info("After saving:", ['result' => $seat]);

    //         return $this->sendResponse($seat, "seat updated successfully");
    //     } catch (\Exception $e) {
    //         Log::error('Error updating seat: ' . $e->getMessage());
    //         return $this->sendResponse(false, "An error occurred while updating the seat", 500);
    //     }
    // }


    /**
     * Remove the specified resource from storage.
     *
     * @param $transport_seat_id
     * @return JsonResponse
     */
        public function destroySeat($transport_seat_id): JsonResponse
    {
        try {
            $seatExists = TransportSeat::where('id', $transport_seat_id)->exists();

            if (!$seatExists) {
                return $this->sendResponse(null, "Seat does not exist");
            }

            $seat = TransportSeat::findOrFail($transport_seat_id);
            
            // Log the seat details before deletion
            Log::info("Attempting to delete transport seat", [
                'seat_id' => $transport_seat_id,
                'seat_data' => $seat->toArray()
            ]);

            $seat->delete();
            return $this->sendResponse($seat, "seat has been deleted");
        } catch (\Exception $e) {
            // Log the error details
            Log::error('Error deleting transport seat', ['error' => $e->getMessage(), 'seat_id' => $transport_seat_id]);
            return $this->sendError('Failed to delete transport seat', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $transport_id
     * @return JsonResponse
     */
    public function getSeatsForTransport(int $transport_id): JsonResponse
    {
        try {
            Log::info("Starting getSeatsForTransport method");

            $transport = Transport::findOrFail($transport_id);

            Log::info("Transport found successfully");

            // Corrected query
            $seats = $transport->transport_seats()->get();

            Log::info("Retrieved seats count: " . count($seats));

            if (!$seats->isNotEmpty()) {
                Log::info("No seats found for transport ID: " . $transport_id);
                return $this->sendResponse([], "No seats found for this transport");
            }

            Log::info("Returning seats data");
            return $this->sendResponse($seats, "All seats for this transport have been retrieved");

        } catch (Exception $e) {
            Log::error('Error getting seat: ' . $e->getMessage());
            Log::error('Error stack trace:', (array)$e->getTraceAsString());
            return $this->sendResponse(false, "An error occurred while getting the seat", 500);
        }
    }


public function getTransportsForOffice(int $office_id): JsonResponse
{
    try {
        $transports = Transport::query()
            ->whereHas('office', function ($query) use ($office_id) {
                $query->where('id', $office_id);
            })
            ->get();

        if (!$transports->isNotEmpty()) {
            return response()->json(['message' => 'No transports found for this office'], 404);
        }

        return $this->sendResponse($transports, "All transports for the office have been retrieved successfully");

    } catch (\Exception $e) {
        Log::error('Error fetching transports for office', ['error' => $e->getMessage()]);
        return $this->sendError('Error fetching transports for office', $e->getMessage());
    }
}

}