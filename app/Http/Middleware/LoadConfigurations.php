<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoadConfigurations
{
    public function handle(Request $request, Closure $next)
    {
        // Load any configurations needed here
        return $next($request);
    }
}
