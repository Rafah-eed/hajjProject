<?php

namespace App\Http\Controllers;

use App\Models\Employee;
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
                        'birth_date' => $pilgrim->birth_date,
                        'health_state' => $pilgrim->health_state,
                        'passport_photo' => $pilgrim->passport_photo,
                        'personal_identity' => $pilgrim->personal_identity,
                        'personal_photo' => $pilgrim->personal_photo,
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


    public function getAllOfficeEmployees(int $office_id): JsonResponse /// GET all employees working in a specific office
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
            $user_id = $user->id;

             // Validate data with correct rules
            $validatedData = $request->validate([
                'birth_date' => 'required|date',
                'health_state' => 'nullable|string|max:255',
                'personal_identity' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
                'personal_photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
                'passport_photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
                'trip_id' => 'required|integer',
                'visa_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg,gif|max:2048',
                'status' => 'nullable|string|max:255',
            ]);

             // Handle passport photo upload
            $passportPhotoPath = null;
            if ($request->hasFile('passport_photo')) {
                $uniqueFilename = uniqid() . '.' . $request->file('passport_photo')->getClientOriginalExtension();
                $passportPhotoPath = Storage::putFileAs('public/pilgrims', $request->file('passport_photo'), $uniqueFilename);
            } else {
                throw new \Exception('Passport photo is required.');
            }

             // Handle personal photo upload
             $personalPhotoPath = null;
             if ($request->hasFile('personal_photo')) {
                 $uniqueFilename = uniqid() . '.' . $request->file('personal_photo')->getClientOriginalExtension();
                 $personalPhotoPath = Storage::putFileAs('public/pilgrims', $request->file('personal_photo'), $uniqueFilename);
             } else {
                 throw new \Exception('Personal photo is required.');
             }

             // Save 'personal_identity' as string
             $personal_identity_value = $validatedData['personal_identity'];

             // Create pilgrim record
             $pilgrim = Pilgrim::create([
                 'user_id' => $user_id,
                 'birth_date' => $validatedData['birth_date'],
                 'health_state' => $validatedData['health_state'],
                 'personal_identity' => $personal_identity_value,
                 'passport_photo' => $passportPhotoPath,
                 'personal_photo' => $personalPhotoPath,
             ]);

             // Handle visa file upload if present
             $visaFilePath = null;
             if ($request->hasFile('visa_file')) {
                 $uniqueVisaFilename = uniqid() . '.' . $request->file('visa_file')->getClientOriginalExtension();
                 $visaFilePath = Storage::putFileAs('public/visas', $request->file('visa_file'), $uniqueVisaFilename);
             }

             // Create visa record
             $visa = Visa::create([
                 'pilgrim_id' => $pilgrim->id,
                 'trip_id' => $validatedData['trip_id'],
                 'visa_file' => $visaFilePath,
                 'status' => $validatedData['status'] ?? 'await',
                 'request_number' => $validatedData['request_number'] ?? '1',
             ]);

             return $this->sendResponse(['pilgrim' => $pilgrim, 'visa' => $visa], "Pilgrim has been stored");
         } catch (\Exception $e) {
             return $this->sendError('Error storing pilgrim data', $e->getMessage());
         }
     }

    public function getPilgrimProfile($pilgrimId): JsonResponse
    {
        try {
            // Fetch the visa record along with the pilgrim and user relation
            $visa = Visa::with(['pilgrim.user', 'trip'])
                ->where('pilgrim_id', $pilgrimId)
                ->first();

            if (!$visa || !$visa->pilgrim) {
                return $this->sendError('Pilgrim not found', 'No pilgrim found with the provided ID.');
            }

            $pilgrim = $visa->pilgrim;

            $profile = [
                'name' => $pilgrim->user->first_name,
                'regiment_name' => $visa->trip->regiment_name,
                'age' => \Carbon\Carbon::parse($pilgrim->birth_date)->age,
                'health_state' => $pilgrim->health_state,
                'phone_number' => $pilgrim->user->phone_number,
            ];

            return $this->sendResponse($profile, "Pilgrim profile retrieved successfully");
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve pilgrim profile', $e->getMessage());
        }
    }

}