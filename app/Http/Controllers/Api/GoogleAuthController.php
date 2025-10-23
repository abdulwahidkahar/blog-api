<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * @group Authentication
 * 
 * API endpoints for Google OAuth authentication.
 */
class GoogleAuthController extends Controller
{
    /**
     * Google OAuth Login
     * 
     * Authenticate user using Google OAuth token.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function googleAuth(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->token);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Google token',
            ], 401);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            $user = User::create([
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
            ]);
        }

        $token = auth()->guard('api')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login with Google successfully',
            'token' => $token,
            'user' => new UserResource($user),
        ], 200);
    }
}
