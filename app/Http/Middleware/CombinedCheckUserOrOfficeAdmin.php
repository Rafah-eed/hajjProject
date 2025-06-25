<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CombinedCheckUserOrOfficeAdmin
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
        $result = app()->call(CheckUserRole::class, [$request, $next, 'user'])
        || app()->call(CheckOfficeAndAdmin::class, [$request, $next]);

        if ($result) {
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
    }
}