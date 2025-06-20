<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pilgrim;
use App\Models\Trip;
use App\Models\Guide;
use App\Models\Guide_Trip;

class RetrievePilgrimGuideMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        $pilgrimId = $user->id;

        $pilgrim = Pilgrim::find($pilgrimId);

        if (!$pilgrim) {
            return response()->json(['error' => 'Unauthorized'], 404);
        }

        $trip = $pilgrim->trips()->first(); // Assuming a many-to-many relationship between Pilgrim and Trip

        if (!$trip) {
            abort(404);
        }

        $guide = $guideTrips->first()->guide; // Assuming Guide_Trip belongs to a Guide

        if (!$guide) {
            abort(404);
        }
        
        $guideTrips = $trip->guide_trips()->get(); // Using the correct relationship name

        if (count($guideTrips) === 0) {
            abort(404);
        }

        // Add the guide to the request
        $request->merge(['guide' => $guide]);

        return $next($request);
    }
}