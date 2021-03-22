<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {

            $autheticated_user = Auth::user();
            $user = User::find($autheticated_user->id);
            $token = $user->createToken('admin')->accessToken;
            return [
                'token' => $token
            ];
        }
        return response(
            [
                'error' => 'invalid credentials'

            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email') +
                ['password' => Hash::make($request->input('password'))]
        );
        return response($user, Response::HTTP_CREATED);
    }
}
