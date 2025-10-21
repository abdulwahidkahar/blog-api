<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
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
