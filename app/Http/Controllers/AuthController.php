<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public static function login(Request $request) 
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Authorized',
                'token' => Auth::user()->createToken('authToken')->plainTextToken
            ], 200);
        }

        return response()->json(['message' => 'Not Authorized'], 401);
    }
}
