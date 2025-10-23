<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

/**
 * @group Authentication
 * 
 * API endpoints for user authentication.
 */
class LoginController extends Controller
{
    /**
     * User Login
     * 
     * Authenticate a user and return JWT token.
     * 
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $validator = $request->validated();

        if(!$token = auth()->guard('api')->attempt($validator)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password is incorrect'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successfully',
            'token' => $token,
            'user' => auth()->guard('api')->user()
        ]);
    }
}
