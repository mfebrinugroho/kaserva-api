<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MenuPolicy
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
        return $user->hasPermission('store.viewAny')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat daftar data menu resto ini.');
    }


    public function view(User $user, Menu $menu): response
    {
        if (!$user->hasPermission('menu.view')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data menu resto ini.');
        }

        return $user->stores()->whereKey($menu->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data menu resto ini.');
    }

    public function create(User $user): response
    {
        return $user->hasPermission('menu.create')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat data menu resto ini.');
    }

    public function update(User $user, Menu $menu): response
    {
        if (!$user->hasPermission('menu.update')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data menu resto ini.');
        }

        return $user->stores()->whereKey($menu->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah data menu resto ini.');
    }

    public function delete(User $user, Menu $menu): response
    {
        if (!$user->hasPermission('menu.delete')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data menu resto ini.');
        }

        return $user->stores()->whereKey($menu->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk menghapus data menu resto ini.');
    }

    public function updateStatus(User $user, Menu $menu): response
    {
        if (!$user->hasPermission('menu.updateStatus')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data menu resto ini.');
        }

        return $user->stores()->whereKey($menu->store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah status aktif menu resto ini.');
    }
}
