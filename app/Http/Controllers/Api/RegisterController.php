<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validator = $request->validated();

        $validator['password'] = bcrypt($validator['password']);

        $user = User::create($validator);

        return new UserResource($user);
    }
}
