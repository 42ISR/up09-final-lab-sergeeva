<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
            'bio' => 'nullable|string'
        ]);

        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'bio' => $fields['bio'] ?? null,
            'role' => 'user'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $fields['username'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Неверный логин или пароль'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $fields = $request->validate([
            'username' => 'sometimes|string|unique:users,username,' . $user->id,
            'bio' => 'nullable|string'
        ]);

        $user->update($fields);

        return response()->json($user);
    }
}