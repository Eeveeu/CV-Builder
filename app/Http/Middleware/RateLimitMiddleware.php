<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Rate limiting: максимум 5 запросов в секунду с одного IP
        $ip = $request->ip();
        $cacheKey = "rate_limit:{$ip}";
        
        $requestCount = cache($cacheKey, 0);
        
        if ($requestCount > 5) {
            return response()->json(['error' => 'Слишком много запросов. Попробуйте позже.'], 429);
        }
        
        cache([$cacheKey => $requestCount + 1], now()->addSecond());
        
        return $next($request);
    }
}
