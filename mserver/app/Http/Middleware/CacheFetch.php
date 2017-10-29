<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Cache;

class CacheFetch
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
        // Perform action
        $key = $this->makeCacheKey($request->fullUrl());
        if (Cache::has($key)) {
        	return response()->json(Cache::get($key));
        }

        return $next($request);
    }

    protected function makeCacheKey($url)
    {
    	return 'route_' . Str::slug($url);
    }
}
