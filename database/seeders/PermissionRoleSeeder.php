<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
  public function run(): void
  {
    $rolePermissions = [
      'super-admin' => [
        '*',
        'user.view',
        'user.create',
        'user.update',
        'user.delete',
      ],

      'owner' => [
        'store.view',
        'store.create',
        'store.update',
        'store.delete',
      ],
    ];

    foreach ($rolePermissions as $roleSlug => $permissions) {

      $role = Role::where('slug', $roleSlug)->firstOrFail();

      if (in_array('*', $permissions)) {
        $permissionIds = Permission::pluck('id');
      } else {
        $permissionIds = Permission::whereIn('slug', $permissions)
          ->pluck('id');
      }

      $role->permissions()->sync($permissionIds);
    }
  }
}
