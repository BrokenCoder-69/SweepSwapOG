<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user()){
            return response()->json([
                'message' => 'Not a user'
            ], 403);
        }
        if ($request->user()->role !== 1) {
            return response()->json([
                'message' => 'Not an Admin'
            ], 403);
        }
        return $next($request);
    }
}



