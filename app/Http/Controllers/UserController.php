<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\Pilgrim;
use App\Models\Trip;
use App\Models\Visa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Response;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getAllPilgrims(): JsonResponse /// GET all pilgrims in the system
    {
        $pilgrims = Pilgrim::with('user')->get();

        if ( is_null($pilgrims))

            return $this->sendResponse(false,  "No data available" ,204);

        return $this->sendResponse($pilgrims, "Pilgrims has been retrieved");

    }


    public function getPilgrimsByTripId(int $tripId): JsonResponse /// GET all pilgrims in the specific trip
    {
        try {
            // Log the incoming trip_id
            Log::info("Attempting to fetch pilgrims data for trip_id: {$tripId}");

            // Find the trip
            $trip = Trip::findOrFail($tripId);

            Log::info("Found trip: ", ['trip' => $trip]);

            // Load the visas for the trip
            $trip->load('visas');

            Log::info("Loaded visas for trip");

                   // Initialize variables
            $totalPilgrims = 0;
            $pilgrimsData = [];

            foreach ($trip->visas as $visa) {
                $pilgrims = Pilgrim::whereHas('visas', function ($query) use ($visa) {
                    $query->where('id', $visa->id);
                })
                ->get();

                foreach ($pilgrims as $pilgrim) {
                    $pilgrimsData[] = [
                        'id' => $pilgrim->id,
                        'first_name' => $pilgrim->user->first_name,
                        'last_name' => $pilgrim->user->last_name,
                        'email' => $pilgrim->user->email,
                        'phone_number' => $pilgrim->user->phone_number,
                        'passport_photo' => $pilgrim->passport_photo,
                        'pilgrim_code' => $pilgrim->pilgrim_code,
                        'created_at' => $pilgrim->created_at,
                        'updated_at' => $pilgrim->updated_at
                    ];
                }

            $totalPilgrims++;
            }
            Log::info("Total pilgrims: {$totalPilgrims}");

            // Prepare the response
            $response = [
                'total_pilgrims' => $totalPilgrims,
                'pilgrims_data' => $pilgrimsData
            ];

                return $this->sendResponse($response, "All pilgrims for the trip have been retrieved successfully");
        } catch (\Exception $e) {
            Log::error('Error fetching pilgrims data', ['error' => $e->getMessage()]);
            return $this->sendError('Error fetching pilgrims data', $e->getMessage());
        }
    }


    public function getAllOfficeEmployees(int $office_id) /// GET all employees working in a specific office
    {
        //
    }

     public function getAllOfficeEmployeesInTrip(int $trip_id, int $office_id) /// GET all employees working in a specific office working in a specific trip
    {
        //
    }

    public function getMyGuide(): JsonResponse
    {
        try {

            $user = Auth::user();

        if (!$user || !$user->id) {
            return response()->json(['error' => 'Unauthorized'], 404);
        }

            $pilgrim = Pilgrim::where('user_id', $user->id)->first();

            if (!$pilgrim) {
                return response()->json(['error' => 'No pilgrim found for this user'], 404);
            }

        $visa = Visa::where('pilgrim_id', $pilgrim->id)->first();

        $tripId= $visa->trip_id;

        // Get all guides associated with the trip
        $guides = Guide::where('trip_id', $tripId)->first();

        return $this->sendResponse($guides, "All guides for the trip have been retrieved successfully");

        } catch (\Exception $e) {
            Log::error('Error fetching guide data', ['error' => $e->getMessage()]);
            return $this->sendError('Error fetching guide data', $e->getMessage());
        }
    }


    //TODO : CREATE PILGRIM AND EMPLOYEE

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */


    public function createPilgrim(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Generate a random 5-digit code
            $randomCode = rand(10000, 99999);

            // Validate request
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id', // Ensure user_id exists in users table
                'passport_photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
                'trip_id' => 'nullable|integer',
                'visa_file' => 'nullable|image|mimes:pdf|max:2048',
                'status' => 'nullable|string|max:255',

            ]);

            // Create pilgrim record with random code
            $pilgrim = Pilgrim::create([
                'passport_photo' => $validatedData['passport_photo'],
                'pilgrim_code' => $randomCode,
                'user_id' => $validatedData['user_id']
            ]);

            // Store passport photo
            if ($request->hasFile('passport_photo')) {
                $uniqueFilename = uniqid() . '.' . $request->file('passport_photo')->getClientOriginalExtension();
                $path = Storage::putFileAs('public/pilgrims', $request->file('passport_photo'), $uniqueFilename);
                $pilgrim->update(['passport_photo' => $path]);
            }


            // Create visa record associated with pilgrim
            $visa = Visa::create([
                'pilgrim_id' => $pilgrim->id,
                'trip_id' => $validatedData['trip_id'] ?? null,
                'visa_file' => $validatedData['visa_file'] ?? null,
                'status' => $validatedData['status'] ?? 'await',
                'request_number' => $validatedData['request_number'] ?? '1',
            ]);

             // Store visa file
            if ($request->hasFile('visa_file') && !is_null($visa)) {
                $uniqueVisaFilename = uniqid() . '.' . $request->file('visa_file')->getClientOriginalExtension();
                $path = Storage::putFileAs('public/visas', $request->file('visa_file'), $uniqueVisaFilename);
                $visa->update(['visa_file' => $path]);
            }

            return $this->sendResponse(['pilgrim' => $pilgrim, 'visa' => $visa], "Pilgrim has been stored");
        } catch (\Exception $e) {
            return $this->sendError('Error storing pilgrim data', $e->getMessage());
        }
    }


//    public function retrieveVisaFile(int $visa_id): JsonResponse
//    {
//    $pilgrimPhotoPath = $pilgrim->passport_photo;
//    $visaFilePath = $visa->visa_file;
//
//    if (!empty($pilgrimPhotoPath)) {
//        $pilgrimPhoto = Storage::get($pilgrimPhotoPath);
//        // Use $pilgrimPhoto as needed
//    }
//
//    if (!empty($visaFilePath)) {
//        $visaFile = Storage::get($visaFilePath);
//        // Use $visaFile as needed
//    }
//        // Method body as shown above
//    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
