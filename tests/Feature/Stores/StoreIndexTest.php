<?php

use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
  $this->seed(RoleSeeder::class);
  $this->seed(PermissionSeeder::class);
  $this->seed(PermissionRoleSeeder::class);
});

it('owner can view own stores', function () {

  $ownerRole = Role::where('slug', 'owner')->firstOrFail();

  $owner = User::factory()->create([
    'role_id' => $ownerRole->id,
  ]);

  Sanctum::actingAs($owner);

  $stores = Store::factory()->count(3)->create();

  $owner->stores()->attach($stores->pluck('id'));

  $response = $this->getJson('/api/stores');

  $response
    ->assertOk()
    ->assertJsonCount(3, 'data');
});

// it('customer cannot view store list');

// it('guest cannot view store list');

// it('supports pagination');

// it('supports searching by name');

// it('supports sorting');
