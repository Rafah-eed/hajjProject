<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Guide;
use App\Models\RateGuide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RateController extends BaseController
{


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $guide_id
     * @return JsonResponse
     */
    public function rateGuide(Request $request, $guide_id): JsonResponse
    {
        $user = Auth::user();
        $user_id = $user->id;

        try {
            $validatedData = $request->validate([
                'rate' => ['required', 'numeric', 'min:1', 'max:5'],
                'comment' => ['required', 'string', 'max:255'],
            ]);
        
            // Log the validated data
            Log::info("Validated data: " . json_encode($validatedData));
        
            // Check if validatedData is an array
            if (is_array($validatedData)) {
                $rate = new RateGuide();
                try {
                    $rate->fill($validatedData)->setAttribute('user_id', $user_id)->setAttribute('guide_id', $guide_id)->save();
                    Log::info("Rate guide created successfully");
                } catch (\Exception $e) {
                    Log::error("Failed to save rate guide: " . $e->getMessage());
                    throw $e; // Re-throw the exception
                }
            } else {
                // If it's not an array, it should be a collection
                $rate = $validatedData;
                try {
                    $rate->fill(['user_id' => $user_id, 'guide_id' => $guide_id])->save();
                    Log::info("Rate guide created successfully");
                } catch (\Exception $e) {
                    Log::error("Failed to save rate guide: " . $e->getMessage());
                    throw $e; // Re-throw the exception
                }
            }
        
            return $this->sendResponse([], "Rate guide has been created successfully");
        } catch (\Exception $e) {
            Log::error('Error creating rate guide: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($e instanceof ValidationException) {
                return $this->sendResponse(false, $e->errors(), 422);
            }
            
            return $this->sendResponse(false, "An unexpected error occurred while creating the rate guide.", 500);
        }

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rateTrip($id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRateGuide($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRateTrip($id)
    {
        //
    }
}