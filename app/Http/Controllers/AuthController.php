<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $role = Role::where('slug', 'customer')->first();

        $validated['role_id'] = $role->id;

        $user = User::create($validated);

        // $token = $user->createToken($request->name);
        $token = $user->createToken('api-token')->plainTextToken;


        return response()->json([
            'success' => true,
            'message' => 'Registered successfully',
            'data' => new AuthResource($user),
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'email' => ['These credentials do not match our records.']
                ]
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();


        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}
