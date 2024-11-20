<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    public function resetPassword(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email'
        ]);

        // Send the reset link
        $status = Password::sendResetLink($request->only('email'));

        // Return a JSON response
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => __('A password reset link has been sent to your email address.')
            ]);
        }

        return response()->json([
            'error' => __('Unable to send reset link. Please check your email address.')
        ], 400);
    }

    // return response()->json(['message'=>'there']);
    public function passwordUpdate(Request $request)
    {
        // Validate the input
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Fire the PasswordReset event
                event(new PasswordReset($user));
            }
        );

        // Check if the reset was successful
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __('Your password has been reset successfully.')
            ]);
        }

        return response()->json([
            'error' => __('Invalid token or email.')
        ], 400);
    }
}
