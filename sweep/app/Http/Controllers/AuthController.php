<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
            'success' => false,
            'message' => 'The provided credentials does not match'], 401);
        }
        // $token = $user->createToken($user->role, ['server:update'])->plainTextToken;
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successfully',
            'token' => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        ]);
    }




    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()], 422);
        }
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 0, // default role
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        ]);
    }









    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
            'success' => false,
            'message' => 'You are not registered with us.']);
        }
        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent.'  // Placeholder
        ]);
    }











    public function reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',  // Add confirmation for security
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
            'success' => false,
            'message' => 'You are not registered with us.']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.'
        ]);
    }












    public function update(Request $request)
{
    // $user = User::where('email', $request->email)->first();
    $user = $request->user();   //Get authenticated user
        
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized user.'
        ], 401);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        // 'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed', // Password is optional, but if provided, must be confirmed
    ]);

    $user->name = $request->name;
    // $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully.',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]
    ]);
}



















    public function getUser(Request $request)
{
    return response()->json($request->user());
}




}
