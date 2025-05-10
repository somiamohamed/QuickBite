<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->isDownForMaintenance()) {
            return response('Be right back!', 503);
        }

        return $next($request);
    }
}
