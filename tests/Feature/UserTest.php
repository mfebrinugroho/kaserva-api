<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
  $superAdminRole = Role::factory()->create([
    'slug' => UserRole::SuperAdmin->value,
    'name' => 'Super Admin',
  ]);

  $this->superAdminUser = User::factory()
    ->for($superAdminRole)
    ->create();
});


test('Super admin can get users', function () {
  // Arrange = Siapkan Kondisi
  Sanctum::actingAs($this->superAdminUser);

  $role = Role::factory()->create([
    'slug' => UserRole::Customer->value,
    'name' => 'Customer',
  ]);

  User::factory()
    ->count(5)
    ->for($role)
    ->create();

  // Act = Jalankan sesuatu
  $response = $this->getJson('/api/users');

  // Assert = Pastikan hasilnya benar
  $response->assertOk();
});

test('user can be created', function () {
  // ARRANGE
  Sanctum::actingAs($this->superAdminUser);

  $role = Role::factory()->create([
    'slug' => UserRole::Customer->value,
    'name' => 'Customer',
  ]);

  // ACT
  $response = $this->postJson('/api/users', [
    'name' => 'Puput',
    'email' => 'puput@example.com',
    'password' => 'password',
    'password_confirmation' => 'password',
    'role_id' => $role->id,
  ]);

  // ASSERT
  $response->assertCreated();

  $this->assertDatabaseHas('users', [
    'name' => 'Puput',
    'email' => 'puput@example.com',
  ]);
});

test('user can be updated', function () {
  // ARRANGE
  Sanctum::actingAs($this->superAdminUser);

  $role = Role::factory()->create([
    'slug' => UserRole::Customer->value,
    'name' => 'Customer',
  ]);

  $user = User::factory()->create([
    'name' => 'Puput',
    'email' => 'puput@example.com',
    'password' => Hash::make('password'),
    'role_id' => $role->id,
  ]);

  // ACT
  $response = $this->patchJson(
    "/api/users/{$user->id}",
    [
      'name' => 'Puput Kurniawati',
      'email' => 'puput@example.com',
      'role_id' => $role->id,
    ]
  );

  // ASSERT
  $response->assertOk();

  $this->assertDatabaseHas('users', [
    'name' => 'Puput Kurniawati',
  ]);
});

test('user can see detail user', function () {
  // ARRANGE
  Sanctum::actingAs($this->superAdminUser);

  $role = Role::factory()->create([
    'slug' => UserRole::Customer->value,
    'name' => 'Customer',
  ]);

  $user = User::factory()->create([
    'name' => 'Puput',
    'email' => 'puput@example.com',
    'password' => Hash::make('password'),
    'role_id' => $role->id,
  ]);

  // ACT
  $response = $this->getJson("/api/users/{$user->id}");

  // ASSERT
  $response
    ->assertOk()
    ->assertJsonFragment([
      'name' => 'Puput',
      'email' => 'puput@example.com',
    ]);
});

test('user can be deleted', function () {
  $role = Role::factory()->create([
    'slug' => UserRole::Customer->value,
    'name' => 'Customer',
  ]);

  $user = User::factory()->create([
    'role_id' => $role->id,
  ]);

  Sanctum::actingAs($this->superAdminUser);

  $response = $this->deleteJson(
    "/api/users/{$user->id}"
  );

  $response
    ->assertOk()
    ->assertJson([
      'success' => true,
      'message' => 'User deleted successfully',
    ]);

  $this->assertDatabaseMissing('users', [
    'id' => $user->id,
  ]);
});
