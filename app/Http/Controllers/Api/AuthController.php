<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Register New User
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|min:5|max:105',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:25|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($user) {
            return response()->json(['message' => 'User Registered Successfully'], 200);
        } else {
            return response()->json(['message' => 'Unable to register User'], 400);
        }
    }

    /**
     * User Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            return response()->json(['message' => 'Unable to login User'], 400);
        }

        $user = Auth::user();

        $token = $user->createToken('My Api Token')->plainTextToken;

        $auth_user = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json(['message' => 'User Logged in successfully', 'user_detail' => $auth_user]);
    }

    /**
     * Fetching user profile
     */
    public function userProfile()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json(['message' => 'User profile fetched successfully', 'user-detail' => $user]);
        } else {
            return response()->json(['message' => 'User profile can not be fetched']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function userLogout()
    {
        $user = Auth::user();

        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'User logged out successfully']);
        } else {
            return response()->json(['message' => 'Unable to logout']);
        }
    }
}
