<?php

namespace App\Services\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;

class JwtAccessTokenService
{
  private string $secret;
  private int $ttl;
  private string $algorithm;

  public function __construct()
  {
    $this->secret = config('jwt.access.secret');
    $this->ttl = config('jwt.access.ttl');
    $this->algorithm = config('jwt.access.algorithm');
  }

  public function generate(User $user): string
  {
    $now = now()->timestamp;

    $payload = [
      'iss' => config('app.url'),
      'sub' => (string) $user->id,
      'jti' => (string) Str::uuid(),
      'type' => 'access',
      'iat' => $now,
      'exp' => $now + $this->ttl,
    ];

    return JWT::encode(
      $payload,
      $this->secret,
      $this->algorithm
    );
  }

  public function decode(string $token): object
  {
    return JWT::decode(
      $token,
      new Key($this->secret, $this->algorithm)
    );
  }
}
