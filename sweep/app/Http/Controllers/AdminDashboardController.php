<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome to Admin Dashboard',
            'user' => $request->user()
        ]);
    }
}