<?php

// use App\Models\Role;
// use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(RefreshDatabase::class);

// beforeEach(function () {
//   Role::factory()->create([
//     'slug' => 'customer',
//     'name' => 'Customer',
//   ]);
// });

// it('can register a new user', function () {

//   $response = $this->postJson('/api/register', [
//     'name' => 'Febri',
//     'email' => 'febri@test.com',
//     'password' => 'password',
//     'password_confirmation' => 'password',
//   ]);

//   $response
//     ->assertCreated()
//     ->assertJsonStructure([
//       'success',
//       'message',
//       'data' => [
//         'user',
//         'token',
//       ]
//     ]);

//   $this->assertDatabaseHas('users', [
//     'email' => 'febri@test.com',
//   ]);
// });

// it('requires all required fields', function () {

//   $response = $this->postJson('/api/register', []);

//   $response
//     ->assertStatus(422)
//     ->assertJsonValidationErrors([
//       'name',
//       'email',
//       'password',
//     ]);
// });

// it('requires unique email', function () {

//   User::factory()->create([
//     'email' => 'admin@test.com',
//   ]);

//   $response = $this->postJson('/api/register', [
//     'name' => 'Admin',
//     'email' => 'admin@test.com',
//     'password' => 'password',
//     'password_confirmation' => 'password',
//   ]);

//   $response
//     ->assertStatus(422)
//     ->assertJsonValidationErrors('email');
// });
