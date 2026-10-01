<?php

namespace App\Services\Auth;

use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
  public function __construct(
    private JwtAccessTokenService $accessTokenService,
    private JwtRefreshTokenService $refreshTokenService,
  ) {}

  public function login(
    string $email,
    string $password,
    Request $request,
  ): array {
    $user = User::where('email', $email)->first();

    if (! $user || ! Hash::check($password, $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['Email atau password salah.'],
      ]);
    }

    // Access Token
    $accessToken = $this->accessTokenService->generate($user);

    // Refresh Token
    $refresh = $this->refreshTokenService->generate($user);

    // Simpan refresh token ke database
    Session::create([
      'user_id' => $user->id,
      'jti' => $refresh['jti'],
      'token_hash' => hash('sha256', $refresh['token']),
      'ip_address' => $request->ip(),
      'user_agent' => $request->userAgent(),
      'expires_at' => now()->addSeconds(
        config('jwt.refresh.ttl')
      ),
    ]);

    return [
      'user' => $user,
      'access_token' => $accessToken,
      'refresh_token' => $refresh['token'],
    ];
  }
}
