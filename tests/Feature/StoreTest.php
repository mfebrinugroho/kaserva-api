<?php

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
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

test('Super admin can get stores', function () {
  // Arrange = Siapkan Kondisi
  Sanctum::actingAs($this->superAdminUser);

  Store::factory()
    ->count(5)
    ->create();

  // Act = Jalankan sesuatu
  $response = $this->getJson('/api/stores');

  // Assert = Pastikan hasilnya benar
  $response->assertOk();
});

test('store can be created', function () {
  // ARRANGE
  Sanctum::actingAs($this->superAdminUser);

  // ACT
  $response = $this->postJson('/api/stores', [
    'name' => 'Toko Baru',
    'slug' => 'toko-baru',
    'description' => 'Toko baru untuk mengetest sebuah sistem',
    'address' => 'Jalan darussalam 4 gang alpokat',
    'phone' => '085334346667',
    'is_active' => true,
  ]);

  // ASSERT
  $response->assertCreated();

  $this->assertDatabaseHas('stores', [
    'name' => 'Toko Baru',
    'slug' => 'toko-baru',
  ]);
});
