<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use App\Services\Auth\JwtAccessTokenService;

beforeEach(function () {
  $role = Role::factory()->create([
    'slug' => UserRole::Owner->value,
    'name' => 'Owner',
  ]);

  $this->user = User::factory()
    ->for($role)
    ->create([
      'email' => 'owner@example.com',
      'password' => 'password123',
    ]);

  $this->credentials = [
    'email' => $this->user->email,
    'password' => 'password123',
  ];
});

test('login returns user data and a valid access token without exposing credentials', function () {
  $response = $this->postJson(
    '/api/staff/auth/login',
    $this->credentials
  );

  $response
    ->assertOk()
    ->assertJsonPath('success', true)
    ->assertJsonPath('message', 'Login berhasil')
    ->assertJsonPath('data.user.id', $this->user->id)
    ->assertJsonPath('data.user.email', $this->user->email);

  expect($response->json('data.user'))
    ->not->toHaveKeys([
      'password',
      'remember_token',
    ]);

  $accessToken = $response->json('data.access_token');

  expect($accessToken)
    ->toBeString()
    ->not->toBeEmpty();

  // Pastikan access token benar-benar dapat diverifikasi
  $payload = app(JwtAccessTokenService::class)
    ->decode($accessToken);

  expect((string) $payload->sub)
    ->toBe((string) $this->user->id);

  expect($payload->type)
    ->toBe('access');

  // Login menyimpan refresh token sebagai session dengan hash token.
  $this->assertDatabaseCount('sessions', 1);

  $this->assertDatabaseHas('sessions', [
    'user_id' => $this->user->id,
    'revoked_at' => null,
  ]);

  // Access token dapat digunakan untuk endpoint protected
  $this->getJson(
    '/api/staff/auth/me',
    [
      'Authorization' => 'Bearer ' . $accessToken,
    ]
  )
    ->assertOk()
    ->assertJsonPath('data.id', $this->user->id);
});

test('login rejects incorrect credentials and does not create a session', function () {
  $response = $this->postJson('/api/staff/auth/login', [
    'email' => $this->user->email,
    'password' => 'wrong-password',
  ]);

  $response
    ->assertUnauthorized()
    ->assertJson([
      'success' => false,
      'message' => 'Email atau password salah.',
    ]);

  $this->assertDatabaseCount('sessions', 0);
});

test('login requires an email and password', function () {
  $this->postJson('/api/staff/auth/login', [])
    ->assertUnprocessable()
    ->assertJsonValidationErrors(['email', 'password']);

  $this->assertDatabaseCount('sessions', 0);
});
