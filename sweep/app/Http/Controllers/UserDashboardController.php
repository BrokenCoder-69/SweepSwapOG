<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome to User Dashboard',
            'user' => $request->user()
        ]);
    }
}