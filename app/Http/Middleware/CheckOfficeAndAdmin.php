<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use App\Models\Guide;
use App\Models\Office;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckOfficeAndAdmin
{
    public function handle(Request $request, Closure $next, $role = [])
    {
        // Get the authenticated user
        $user = Auth::user();

        // If the user is not authenticated, deny access
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // For superAdmin users, allow access regardless of office_id
        if ($user->role === 'superAdmin') {
            return $next($request);
        }

        // Get the office_id from the request
        $officeId = $request->route('office_id');

        // Find the office
        $office = Office::where('id', $officeId)->first();

        Log::info("Office exists", ['office' => $office]);

        // Check if the user has an associated employee record
        $employee = Employee::where('user_id', $user->id)->first();
        Log::info("Found employee:", ['employee' => $employee]);

        $guide = Guide::where('user_id', $user->id)->first();
        Log::info("Found guide:", ['guide' => $guide]);

        // For non-superAdmin users, check if employee or guide record exists
        if (!($employee || $guide)) {
            return response()->json(['error' => 'Forbidden. Employee Or Guide record not found'], 403);
        }

        Log::info("Checking office authorization", [
            'user_role' => $user->role,
            'office_id' => $officeId,
            'guide_office_id' => $guide?->office_id ?? null,
            'employee_office_id' => $employee?->office_id ?? null
        ]);
        
        if ((!$guide || $guide->office_id === $officeId) && (!$employee || $employee->office_id === $officeId)) {
            return response()->json(['error' => 'Forbidden. Not authorized for this office'], 403);
        }

        // If all checks pass, allow the action
        return $next($request);
    }
}