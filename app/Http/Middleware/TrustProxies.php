<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class TrustProxies
{
    public function handle(Request $request, \Closure $next)
    {
        return $next($request);
    }
}
