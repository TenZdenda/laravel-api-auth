<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function register(UserStoreRequest $request)
    {
        $user = User::create([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'access_token' => $accessToken], 200);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $loginData= [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (!auth()->attempt($loginData)) {
            return response()->json(['message' => 'Invalid Credentials'], 500);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json(['user' => auth()->user(), 'access_token' => $accessToken], 200);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json(['success' => trans('auth.logout')], 200);
    }

}
