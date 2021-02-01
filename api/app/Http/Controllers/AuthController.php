<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Login an existing User.
     *
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([], Response::HTTP_NO_CONTENT);
        }

        return response()->json([
            'error' => [
                'message' => trans('auth.failed')
            ]
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Logout a currently authenticated User.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
