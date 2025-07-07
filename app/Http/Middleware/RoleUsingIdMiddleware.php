<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleUsingIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $role
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, $role): JsonResponse
    {
        $userId = $request->route('user_id');

        $user = User::find($userId);



        if (!$user) {
            return response()->json(['error' => 'user does not exist'], 401);
        }

        if ($user->role == $role){
            return $next($request);
        }
        else {
            return response()->json(['error' => 'the selected user is not authorized'], 403);
        }

    }
}
