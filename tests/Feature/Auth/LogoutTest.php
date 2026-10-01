<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\Session;
use App\Models\User;

beforeEach(function () {
  $this->role = Role::factory()->create(['slug' => UserRole::Owner->value, 'name' => 'Owner']);
  $this->user = User::factory()->for($this->role)->create([
    'email' => 'owner@example.com',
    'password' => 'password123',
  ]);
});

test('user can logout using a token issued by login', function () {
  $login = $this->postJson('/api/v1/staff/login', [
    'email' => $this->user->email,
    'password' => 'password123',
  ])->assertOk();
  $refreshCookie = $login->getCookie('refresh_token', decrypt: false);
  $accessToken = $login->json('data.access_token');

  expect($refreshCookie)->not->toBeNull();
  expect($accessToken)->toBeString()->not->toBeEmpty();

  $this->withCredentials()
    ->withUnencryptedCookie('refresh_token', $refreshCookie->getValue())
    ->postJson('/api/v1/staff/logout', [], [
      'Authorization' => 'Bearer ' . $accessToken,
    ])
    ->assertOk()
    ->assertJsonPath('message', 'Logout berhasil!');

  $session = Session::where('user_id', $this->user->id)->firstOrFail();
  expect($session->revoked_at)->not->toBeNull();
});

test('guest cannot logout using a refresh cookie alone', function () {
  $login = $this->postJson('/api/v1/staff/login', [
    'email' => $this->user->email,
    'password' => 'password123',
  ])->assertOk();
  $refreshCookie = $login->getCookie('refresh_token', decrypt: false);

  $this->withCredentials()
    ->withUnencryptedCookie('refresh_token', $refreshCookie->getValue())
    ->postJson('/api/v1/staff/logout')
    ->assertUnauthorized();

  $this->assertDatabaseHas('sessions', [
    'user_id' => $this->user->id,
    'revoked_at' => null,
  ]);
});

test('logout revokes only the refresh session from its cookie', function () {
  $firstLogin = $this->postJson('/api/v1/staff/login', [
    'email' => $this->user->email,
    'password' => 'password123',
  ])->assertOk();
  $secondLogin = $this->postJson('/api/v1/staff/login', [
    'email' => $this->user->email,
    'password' => 'password123',
  ])->assertOk();

  $firstRefreshCookie = $firstLogin->getCookie('refresh_token', decrypt: false);
  $secondRefreshCookie = $secondLogin->getCookie('refresh_token', decrypt: false);
  $firstAccessToken = $firstLogin->json('data.access_token');

  $this->withCredentials()
    ->withUnencryptedCookie('refresh_token', $firstRefreshCookie->getValue())
    ->postJson('/api/v1/staff/logout', [], [
      'Authorization' => 'Bearer ' . $firstAccessToken,
    ])
    ->assertOk();

  $this->assertDatabaseCount('sessions', 2);
  $firstSession = Session::where(
    'token_hash',
    hash('sha256', $firstRefreshCookie->getValue())
  )->firstOrFail();
  $secondSession = Session::where(
    'token_hash',
    hash('sha256', $secondRefreshCookie->getValue())
  )->firstOrFail();

  expect($firstSession->revoked_at)->not->toBeNull();
  expect($secondSession->revoked_at)->toBeNull();
});
