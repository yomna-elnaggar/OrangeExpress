<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);


        $throttleKey = Str::lower($validatedData['phone']) . '|' . $request->ip();


        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'message' => 'Too many login attempts. Try again in ' . RateLimiter::availableIn($throttleKey) . ' seconds.',
            ], 429);
        }

        $user = User::where('phone', $validatedData['phone'])->first();

        if (!$user) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'phone' => ['The phone number is not registered.'],
            ]);
        }

        if (!Hash::check($validatedData['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        RateLimiter::clear($throttleKey);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully as ' . $user->role,
            'user'    => new UserResource($user),
            'token'   => $token,
        ]);
    }
}
