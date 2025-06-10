<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $role
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, $role=null): JsonResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->hasRole('admin')) {
            return response()->json(['error' => 'Forbidden. Admins only.'], 403);
        }

        return $next($request);
    }
}
