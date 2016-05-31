<?php

namespace HighIdeas\UsersOnline\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UsersOnline
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

        if (Auth::user()) {
            Auth::user()->setCache(config('session.lifetime'));
        }

        return $response;
    }
}
