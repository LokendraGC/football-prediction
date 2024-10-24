<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function admin_login(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credential)) {
            return response(['message' => 'Wrong Credential'], 404);
        }

        $user = Auth::user();

        // Check if the user is an admin
        if ($user->is_admin !== 1) {
            return response(['message' => 'Only admin can access this page'], 403); 
        }

        // Create a Sanctum token
        $token = $user->createToken('My Api Token')->plainTextToken;

        return response(['message' => 'You are logged in Successfully', 'user' => $user, 'token' => $token], 200);
    }
}
