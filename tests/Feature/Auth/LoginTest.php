<?php

use App\Models\User;

it('can login', function () {

  $user = User::factory()->create([
    'email' => 'user@test.com',
    'password' => bcrypt('password'),
  ]);

  $response = $this->postJson('/api/login', [
    'email' => 'user@test.com',
    'password' => 'password',
  ]);

  $response
    ->assertOk()
    ->assertJsonStructure([
      'success',
      'message',
      'token',
      'data' => [
        'user',
      ]
    ]);
});

it('cannot login with wrong password', function () {

  User::factory()->create([
    'email' => 'user@test.com',
    'password' => bcrypt('password'),
  ]);

  $response = $this->postJson('/api/login', [
    'email' => 'user@test.com',
    'password' => '123456',
  ]);

  $response->assertUnauthorized();
});

it('cannot login with unknown email', function () {

  $response = $this->postJson('/api/login', [
    'email' => 'unknown@test.com',
    'password' => 'password',
  ]);

  $response->assertUnauthorized();
});
