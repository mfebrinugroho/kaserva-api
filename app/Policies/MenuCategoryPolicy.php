<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\MenuCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MenuCategoryPolicy
{

    public function before(User $user): bool|null
    {
        if ($user->hasRole(UserRole::SuperAdmin)) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): response
    {
        return $user->hasPermission('menuCategory.viewAny')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat daftar data kategori menu resto ini.');
    }

    public function view(User $user, MenuCategory $menuCategory): response
    {
        if (!$user->hasPermission('menuCategory.view')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data kategori menu resto ini.');
        }

        return $user->stores()->whereKey($menuCategory->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data kategori menu resto ini.');
    }

    public function create(User $user): response
    {
        return $user->hasPermission('menuCategory.create')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat data kategori menu resto ini.');
    }

    public function update(User $user, MenuCategory $menuCategory): response
    {
        if (!$user->hasPermission('menuCategory.update')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data kategori menu resto ini.');
        }

        return $user->stores()->whereKey($menuCategory->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah data kategori menu resto ini.');
    }

    public function delete(User $user, MenuCategory $menuCategory): response
    {
        if (!$user->hasPermission('menuCategory.delete')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data kategori menu resto ini.');
        }

        return $user->stores()->whereKey($menuCategory->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk menghapus data kategori menu resto ini.');
    }

    public function viewOptions(User $user, MenuCategory $menuCategory): response
    {
        if (!$user->hasPermission('menuCategory.viewOptions')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data kategori menu resto ini.');
        }

        return $user->stores()->whereKey($menuCategory->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat opsi list kategori menu resto ini.');
    }

    public function updateStatus(User $user, MenuCategory $menuCategory): response
    {
        if (!$user->hasPermission('menuCategory.updateStatus')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data kategori menu resto ini.');
        }

        return $user->stores()->whereKey($menuCategory->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah status aktif kategori menu resto ini.');
    }
}
