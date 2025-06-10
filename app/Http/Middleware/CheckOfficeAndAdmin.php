<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOfficeAndAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->hasRole('admin')) {
            return response()->json(['error' => 'Forbidden. Admins only.'], 403);
        }

        // Check if the trip exists
        $tripId = $request->route('trip');
        $trip = \App\Models\Trip::find($tripId);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found.'], 404);
        }

        // Check if user belongs to same office as trip's creator
        if ($user->employee()->office_id !== $trip->office_id) {
            return response()->json(['error' => 'Forbidden. Not authorized for this office.'], 403);
        }

        return $next($request);
    }

}
