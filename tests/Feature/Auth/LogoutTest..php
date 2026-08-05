<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can logout', function () {

  $user = User::factory()->create();

  $token = $user->createToken('test')->plainTextToken;

  $response = $this
    ->withToken($token)
    ->postJson('/api/logout');

  $response->assertOk();

  expect($user->fresh()->tokens)->toHaveCount(0);
});

it('guest cannot logout', function () {

  $this
    ->postJson('/api/logout')
    ->assertUnauthorized();
});
