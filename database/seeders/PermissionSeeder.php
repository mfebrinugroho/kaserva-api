<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
  public function run(): void
  {
    $permissions = [

      // User
      [
        'slug' => 'user.view',
        'name' => 'Lihat User',
      ],
      [
        'slug' => 'user.create',
        'name' => 'Tambah User',
      ],
      [
        'slug' => 'user.update',
        'name' => 'Edit User',
      ],
      [
        'slug' => 'user.delete',
        'name' => 'Hapus User',
      ],

      // Store
      [
        'slug' => 'store.view',
        'name' => 'Lihat Resto',
      ],
      [
        'slug' => 'store.create',
        'name' => 'Tambah Resto',
      ],
      [
        'slug' => 'store.update',
        'name' => 'Edit Resto',
      ],
      [
        'slug' => 'store.delete',
        'name' => 'Hapus Resto',
      ],

    ];

    foreach ($permissions as $permission) {
      Permission::updateOrCreate(
        ['slug' => $permission['slug']],
        $permission
      );
    }
  }
}
