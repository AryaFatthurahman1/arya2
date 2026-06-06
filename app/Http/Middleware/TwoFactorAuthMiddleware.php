<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 2FA disabled for development - just pass through
        return $next($request);
    }
}