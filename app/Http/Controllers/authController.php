<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email', // Ditambahkan koma dan validasi email
            'password' => 'required'
        ]);

        $user = User::where('email', '=', $request->email)->first(); // Diperbaiki penulisan operator '='

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials' // Diperbaiki penulisan pesan
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Diperbaiki penulisan 'delete'
        return response()->json([
            'message' => 'Logout Successfully' // Diperbaiki penulisan 'message'
        ]);
    }

    public function user()
    {
        $data = User::where('id', auth()->user()->id)->first(); // Diperbaiki penulisan '='
        return response()->json($data);
    }
} 