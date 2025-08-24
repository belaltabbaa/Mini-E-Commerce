<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:150|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'role' => 'sometimes|in:admin,user',
            ]
        );
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]
        );
        return response()->json(
            [
                'message' => 'User Created Successfuly',
                'User' => $user,
            ],
            201
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|max:150|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                [
                    'message' => 'invalid email or password'
                ],
                401
            );
        } else {
            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('Auth_token')->plainTextToken;

            return response()->json([
                'message' => 'login successfuly',
                'user' => $user,
                'token' => $token
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logout successfuly'], 200);
    }
}
