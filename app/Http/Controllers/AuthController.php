<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Register and send OTP
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $otp = rand(100000, 999999);

        Otp::create([
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10)
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return response()->json([
            'message' => 'User registered. OTP sent to email.',
            'user' => $user
        ]);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $otp = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (Carbon::now()->greaterThan($otp->expires_at)) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user && !$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        $otp->delete();

        return response()->json([
            'message' => 'OTP verified successfully',
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
