<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Cache;

class CachePut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Perform action
        $key = $this->makeCacheKey($request->fullUrl());
        if (!Cache::has($key)) Cache::put($key, $response->getContent(), 60);

        return $response;
    }

    protected function makeCacheKey($url)
    {
    	return 'route_' . Str::slug($url);
    }
}
