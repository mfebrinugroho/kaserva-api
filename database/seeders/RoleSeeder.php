<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
  public function run(): void
  {
    $roles = [
      [
        'slug' => 'super-admin',
        'name' => 'Super Admin',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'slug' => 'owner',
        'name' => 'Owner',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'slug' => 'admin',
        'name' => 'Admin',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'slug' => 'customer',
        'name' => 'Customer',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'slug' => 'cashier',
        'name' => 'Cashier',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'slug' => 'kitchen',
        'name' => 'Kitchen',
        'created_at' => now(),
        'updated_at' => now(),
      ]
    ];

    foreach ($roles as $role) {
      Role::updateOrCreate(
        ['slug' => $role['slug']],
        $role
      );
    }
  }
}
