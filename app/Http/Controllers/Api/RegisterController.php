<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Authentication
 * 
 * API endpoints for user registration.
 */
class RegisterController extends Controller
{
    /**
     * User Registration
     * 
     * Register a new user account.
     * 
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request)
    {
        $validator = $request->validated();

        $validator['password'] = bcrypt($validator['password']);

        $user = User::create($validator);

        return new UserResource($user);
    }
}
