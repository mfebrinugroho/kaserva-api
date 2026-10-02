<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Auth\JwtAccessTokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateJwt
{
  public function __construct(private JwtAccessTokenService $accessTokenService) {}

  public function handle(Request $request, Closure $next): Response
  {
    $header = $request->header('Authorization');

    if (! $header || ! str_starts_with($header, 'Bearer ')) {
      return response()->json([
        'success' => false,
        'message' => 'Unauthenticated.',
      ], 401);
    }

    $token = substr($header, 7);

    try {
      $payload = $this->accessTokenService->decode($token);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid or expired access token.',
      ], 401);
    }

    if (($payload->type ?? null) !== 'access') {
      return response()->json([
        'success' => false,
        'message' => 'Invalid token type.',
      ], 401);
    }

    $user = User::where('id', $payload->sub)->first();

    if (! $user) {
      return response()->json([
        'success' => false,
        'message' => 'User tidak ditemukan.',
      ], 401);
    }

    $request->attributes->set('jwt_payload', $payload);
    $request->attributes->set('user', $user);

    return $next($request);
  }
}
