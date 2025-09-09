<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role !== 'user' or Auth::user()->role !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: User access required'
            ], 403);
        }

        return $next($request);
    }
}