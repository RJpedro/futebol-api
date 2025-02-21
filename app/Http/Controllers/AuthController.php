<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public static function login(Request $request) 
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return Controller::return_default([
                'token' => Auth::user()->createToken('authToken')->plainTextToken
            ], 'Authorized.', 200);
        }

        return Controller::return_default([], 'Unauthorized.', 401);
    }
}
