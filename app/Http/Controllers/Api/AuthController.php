<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravolt\Avatar\Facade as Avatar;

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
            'password' => Hash::make($request->password),
            'is_verified' => 0
        ]);

        Avatar::create($request->name)
            ->save(storage_path('app/public/avatar-' . $user->id . '.png'));

        if ($user) {
            return response()->json(['message' => 'User Registered Successfully'], 200);
        } else {
            return response()->json(['message' => 'Unable to register User'], 400);
        }
    }

    public function updateAvatar(Request $request)
    {

        return $request->all();
        
        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate file type and size
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {

            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Store the new avatar
            $file = $request->file('avatar');
            $filePath = 'avatar-' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $filePath); 

            $user->avatar = $filePath;
            $user->save();
        } else {
            Avatar::create($user->name)
                ->save(storage_path('app/public/avatar-' . $user->id . '.png'));

            $user->avatar = 'avatar-' . $user->id . '.png';
            $user->save();
        }
        return response()->json(['message' => 'avatar updated successfully', 'user' => $user]);
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

    public function sendVerifyMail($email)
    {

        // if (Auth::user()) {

        $random = Str::random(40);
        $domain = URL::to('/');
        $url = $domain . "/verify-mail/" . $random;

        $user = User::where('email', $email)->get();

        if ($user) {

            $data['url'] = $url;
            $data['email'] = $email;
            $data['title'] = "Email Verification";
            $data['body'] = "please click here below to verify your mail";

            Mail::send('verify-email', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            $user = User::find($user[0]['id']);
            $user->remember_token = $random;
            $user->save();

            return response()->json(['status' => 'ture', 'message' => 'Mail Sent Successfully']);
        } else {
            return response()->json(['status' => 'false', 'message' => 'User is not available']);
        }
        // } else {
        //     return response()->json(['status' => 'false', 'message' => 'User is not authenticated']);
        // }


    }

    public function verifyToken($token)
    {

        $user = User::where('remember_token', $token)->get();

        if ($user) {
            $date_time = Carbon::now()->format('Y-m-d H:i:s');
            $user = User::find($user[0]['id']);
            $user->remember_token = '';
            $user->is_verified = 1;
            $user->email_verified_at = $date_time;
            $user->save();
            return "<h1>Email Verified</h1>";
        } else {
            return view('404');
        }
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
