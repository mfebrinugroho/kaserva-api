<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserAuthResource;
use App\Models\Role;
use App\Models\Session;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\JwtRefreshTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private JwtRefreshTokenService $refreshTokenService,
    ) {}

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
            'data' => new UserAuthResource($user),
            'token' => $token
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $tokens = $this->authService->login(
            $credentials['email'],
            $credentials['password'],
            $request
        );

        $refreshCookie = cookie(
            'staff_refresh_token',
            $tokens['refresh_token'],
            60 * 24 * 7,
            '/api/staff/auth',
            null,
            app()->environment('production'),
            true,
            false,
            'lax',
        );

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'access_token' =>  $tokens['access_token'],
                'user' => $tokens['user']
            ],
        ], 200)->withCookie($refreshCookie);
    }

    public function refresh(Request $request): JsonResponse
    {
        $refreshToken = $request->cookie('staff_refresh_token');

        if (! $refreshToken) {
            return response()->json([
                'success' => false,
                'message' => 'Refresh token tidak ditemukan.',
            ], 401);
        }

        $result = $this->authService->refresh($refreshToken);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Refresh token tidak valid atau sudah expired.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Access token berhasil diperbarui',
            'data' => [
                'access_token' => $result,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $payload = $request->attributes->get('jwt_payload');

        $user = User::select(['id', 'name', 'email', 'store_id', 'role_id'])
            ->with('stores:id,slug,name', 'role:id,slug,name', 'role.permissions:id,slug,name')
            ->find($payload->sub);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data anda sendiri',
            'data' => new UserAuthResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $refreshToken = $request->cookie('staff_refresh_token');

        $payload = $this->refreshTokenService->decode($refreshToken);

        Session::where('jti', $payload->jti)
            ->update([
                'revoked_at' => now(),
            ]);


        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil!',
        ])->withoutCookie('staff_refresh_token');
    }
}
