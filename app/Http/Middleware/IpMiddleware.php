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
        $ip = $request->ip();

        // Allow localhost and any local network IP (192.168.*)
        if (!in_array($ip, $this->allowedIps) && !str_starts_with($ip, '192.168.')) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}