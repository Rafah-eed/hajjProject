<?php

namespace App\Http\Middleware;

use App\Models\Employee;

use App\Models\Guide;
use App\Models\Trip;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Log;

class CheckOfficeAndAdmin
{
    public function handle(Request $request, Closure $next, $role=null): JsonResponse
    {
        // Get the authenticated user
        $user = Auth::user();

        // If the user is not authenticated, deny access
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get the office_id from the request
        $tripId = $request->trip_id; // Assuming the route parameter is named 'trip_id'
        $trip = Trip::where('id', $tripId)->first();

        // Check if the user has an associated employee record
        $user_id = $user->id;

        Log::info("Attempting to find employee for user_id: {$user_id}");
        $employee = Employee::where('user_id', $user->id)->first();
        Log::info("Found employee: ", ['employee' => $employee]);

        $guide = Guide::where('user_id', $user->id)->first();
        Log::info("Found guide: ", ['guide' => $guide]);



        if (!$employee ||!$guide) {
            return response()->json(['error' => 'Forbidden. Employee Or Guide record not found'], 403);
        }

        // Check if the employee's office_id matches the requested office_id
        if ($employee->office_id !== $trip->office_id || $guide->office_id !== $trip->office_id ) {
            return response()->json(['error' => 'Forbidden. Not authorized for this office'], 403);
        }

        // If all checks pass, allow the action
        return $next($request);
    }
}
