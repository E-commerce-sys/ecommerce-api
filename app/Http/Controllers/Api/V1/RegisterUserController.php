<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Http\Requests\Api\V1\VerifyOtpRequest;
use App\Mail\UserRegistered;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterUserController extends ApiController
{
    public function store(RegisterUserRequest $request) {
        $otp = random_int(100000, 999999);
        $user = User::create([
            ...$request->mappedAttributes(),
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(UserRegistered::$expirationMinutes)
        ]);

        $token = $user->createToken($user->email)->plainTextToken;

        Mail::to($user->email)->queue(new UserRegistered($user, $otp));

        return $this->success(
            [
                'token' => $token,
                'user' => $user
            ],
            'User registered successfully',
            201
        );
    }

    public function verifyOtp(VerifyOtpRequest $request) {
        $user = $request->user();

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email is already verified.',
            ], 409);
        }

        if (!$user->otp_expires_at || Carbon::now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'message' => 'OTP has expired. Please request a new one.',
            ], 422);
        }

        if (!$user->otp || !Hash::check($request->otp, $user->otp)) {
            return response()->json([
                'message' => 'Invalid OTP.',
            ], 422);
        }

        $user->update([
            'email_verified_at' => Carbon::now(),
            'otp'               => null,
            'otp_expires_at'    => null,
        ]);

        return $this->ok([], 'Email verified successfully!');

    }

    public function resendOtp(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp_expires_at && now()->lt($user->otp_expires_at->subMinutes(UserRegistered::$expirationMinutes - 1))) {
            return $this->error([
                'message' => 'Please wait for 1 minute before requesting a new OTP.'
            ], 429);
        }

        $otp = random_int(100000, 999999);
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->queue(new UserRegistered($user, $otp));

        return $this->ok([], 'A new OTP has been sent to your email.');
    }
}
