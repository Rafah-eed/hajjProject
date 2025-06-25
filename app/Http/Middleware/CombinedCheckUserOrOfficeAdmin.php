<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CombinedCheckUserOrOfficeAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $result = app()->call(CheckUserRole::class, [$request, $next, 'user'])
        || app()->call(CheckOfficeAndAdmin::class, [$request, $next]);

        if ($result) {
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
    }
}
