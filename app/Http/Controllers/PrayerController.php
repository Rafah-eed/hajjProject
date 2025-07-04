<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Prayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrayerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $prayers = Prayer::all();

        if ( is_null($prayers))
            return $this->sendResponse(false,  "No data available" ,204);

        return $this->sendResponse($prayers, "Prayers has been retrieved");

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
                'title' => 'required|string|max:255',
                'content' => 'nullable|string|max:1000|regex:/^[\x{0621}-\x{064A}\s]+$/u',
            ]);

            $prayer = new Prayer();
            $prayer->fill($validatedData);
            Log::info("Before saving:", ['prayer' => $prayer]);

            $prayer->save();

            Log::info("After saving:", ['result' => $prayer]);

            return $this->sendResponse($prayer, "Prayer has been added successfully");
        } catch (\Exception $e) {
            Log::error('Error adding prayer: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while adding the prayer", 500);
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $prayer_id
     * @return JsonResponse
     */
    public function update(Request $request, int $prayer_id): JsonResponse
    {
        try {
            DB::enableQueryLog();

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string|max:1000|regex:/^[\x{0621}-\x{064A}\s]+$/u',
            ]);

            $prayer = Prayer::findOrFail($prayer_id);

            Log::info("Before saving:", ['prayer' => $prayer]);

            $prayer->update([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'] ?? null,
            ]);

            Log::info("After saving:", ['result' => $prayer]);

            return $this->sendResponse($prayer, "Prayer updated successfully");
        } catch (\Exception $e) {
            Log::error('Error updating prayer: ' . $e->getMessage());
            return $this->sendResponse(false, "An error occurred while updating the prayer", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $prayer_id
     * @return JsonResponse
     */
    public function destroy($prayer_id): JsonResponse
    {
        $prayer = Prayer::findOrFail($prayer_id);

        $prayer->delete();

        return $this->sendResponse($prayer, "prayer has been deleted");

    }

    public function getPrayerByID($prayer_id): JsonResponse
    {
        $prayer = Prayer::findOrFail($prayer_id);

        if ( is_null($prayer))
            return $this->sendResponse(false,  "No data available" ,204);

        return $this->sendResponse($prayer, "Prayer has been retrieved");


    }
}
