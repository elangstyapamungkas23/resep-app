<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 🔥 REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Register berhasil',
            'data' => $user
        ]);
    }

    // 🔥 LOGIN + TOKEN
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login gagal'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('token-api')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Logout berhasil'
    ]);
    }
}