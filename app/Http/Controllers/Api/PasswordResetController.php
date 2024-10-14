<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function send_reset_password(Request $request)
    {

        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => "Email Doesn't exist",
                'status' => 'failed'
            ]);
        }

        $token = Password::createToken($user);

        // saving data to password reset table
        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('reset', ['token' => $token], function (Message $message) use ($email) {
            $message->subject('Reset Your Password');
            $message->to($email);
        });

        return response()->json([
            'message' => 'Password reset email sent check your mail',
            'status' => 'success'
        ]);
    }

    public function reset(Request $request, $token)
    {
        // delete the token after 1 minutes
        $expiryTime = Carbon::now()->subMinutes(60);
        PasswordReset::where('created_at', '<=', $expiryTime)->delete();

        $request->validate([
            'password' => 'required|confirmed'
        ]);

        $reset_password  = PasswordReset::where('token', $token)->first();

        if (!$reset_password) {
            return response()->json([
                'message' => 'Invalid token',
                'status' => 'failed'
            ]);
        }

        $user = User::where('email', $reset_password->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();


        // delete the token after reseting password
        PasswordReset::where('email', $user->email)->delete();


        return response()->json([
            'message' => 'Password reset successfully',
            'status' => 'success '
        ], 200);
    }
}
