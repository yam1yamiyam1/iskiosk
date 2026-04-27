<?php

namespace App\Http\Middleware;

use Closure;

class IpMiddleware
{
    protected $allowedIps = [
        '127.0.0.1',
    ];

    public function handle($request, Closure $next)
    {
        if (!in_array($request->ip(), $this->allowedIps)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}