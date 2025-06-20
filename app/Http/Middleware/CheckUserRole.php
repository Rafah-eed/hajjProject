<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $roles
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, $roles): JsonResponse
    {
        Log::debug('Data type:', ['data_type' => gettype($roles)]);

        $user = auth()->user();


        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (is_string($roles)) {
            $roles = explode('|', $roles);
        }

        if (!is_array($roles)) {
            throw new \InvalidArgumentException('Roles must be an array or string separated by "|"');
        }

        foreach ($roles as $role) {
            if (in_array($role, explode('|', $user->role))) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Forbidden'], 403);

    }
}