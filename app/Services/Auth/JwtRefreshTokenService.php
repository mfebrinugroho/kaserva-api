<?php

namespace App\Services\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;

class JwtRefreshTokenService
{
  private string $secret;
  private int $ttl;
  private string $algorithm;

  public function __construct()
  {
    $this->secret = config('jwt.refresh.secret');
    $this->ttl = config('jwt.refresh.ttl');
    $this->algorithm = config('jwt.refresh.algorithm');
  }

  public function generate(User $user): array
  {
    $now = now()->timestamp;

    $jti = (string) Str::uuid();

    $expiresAt = $now + $this->ttl;

    $payload = [
      'iss' => config('app.url'),
      'sub' => (string) $user->id,
      'jti' => $jti,
      'type' => 'refresh',
      'iat' => $now,
      'exp' => $expiresAt,
    ];

    $token = JWT::encode(
      $payload,
      $this->secret,
      $this->algorithm
    );

    return [
      'token' => $token,
      'jti' => $jti,
      'expires_at' => $expiresAt,
    ];
  }

  public function decode(string $token): object
  {
    return JWT::decode(
      $token,
      new Key($this->secret, $this->algorithm)
    );
  }
}
