<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Http\Requests\Api\V1\VerifyOtpRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Mail\UserRegistered;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterUserController extends ApiController
{
    public function store(RegisterUserRequest $request)
    {
        $mappedAttributes = $request->mappedAttributes();
        $otp = random_int(100000, 999999);
        $otpData = [
            'otp'            => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(UserRegistered::$expirationMinutes),
        ];

        // Check if a soft-deleted user exists with the same email
        $existingUser = User::withTrashed()
            ->where('email', $mappedAttributes['email'])
            ->first();

        $isRestored = false;

        if ($existingUser?->trashed()) {
            $existingUser->restore();
            $existingUser->update([
                ...$mappedAttributes,
                ...$otpData,
            ]);
            $user = $existingUser->fresh();
            $isRestored = true;
        } else {
            $user = User::create([
                ...$mappedAttributes,
                ...$otpData,
            ]);
        }

        Mail::to($user->email)->queue(new UserRegistered($user, $otp));

        return $this->success(
            [
                'user'         => new UserResource($user),  
                'is_restored'  => $isRestored,
            ],
            $isRestored
                ? 'User account restored successfully'
                : 'User registered successfully',
            $isRestored ? 200 : 201
        );
    }

    public function verifyOtp(VerifyOtpRequest $request) {
        $user = User::where('email', $request->input('email'))->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email is already verified.',
            ], 409);
        }

        if (!$user->otp_expires_at || Carbon::now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'message' => 'OTP has expired. Please request a new one.',
            ], 422);
        }

        if (!is_null($user->blocked_at)) {
            return $this->error([
                'message' => 'Your account has been blocked.',
                'status' => 403,
                'source' => 'blockedUser'
            ], 403);
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

        $token = $user->createToken(
            $user->email,
        )->plainTextToken;

        return $this->ok([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'Email verified successfully!');

    }

    public function resendOtp(Request $request) {
        $request->validate([
            'email' => ['required', 'exists:users,email'],
        ]);
        $user = User::where('email', $request->input('email'))->first();

        if ($user->email_verified_at) {
            return $this->error('Email is already verified.', 409);
        }

        if ($user->otp_expires_at && now()->lt($user->otp_expires_at->subMinutes(UserRegistered::$expirationMinutes - 1))) {
            return $this->error([
                'message' => 'Please wait for 1 minute before requesting a new OTP.'
            ], 429);
        }

        $otp = random_int(100000, 999999);
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(UserRegistered::$expirationMinutes);
        $user->save();

        Mail::to($user->email)->queue(new UserRegistered($user, $otp));

        return $this->ok([], 'A new OTP has been sent to your email.');
    }
}
