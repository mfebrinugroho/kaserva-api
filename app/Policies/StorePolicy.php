<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StorePolicy
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
            : Response::deny('Anda tidak memiliki izin untuk melihat daftar data resto/toko.');
    }

    public function view(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.view')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
    }

    public function create(User $user): response
    {
        return $user->hasPermission('store.create')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat data resto/toko.');
    }

    public function update(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.update')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengedit data resto/toko ini.');
    }

    public function delete(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.delete')) {
            return Response::deny('Anda tidak memiliki izin untuk menghapus data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk menghapus data resto/toko ini.');
    }

    public function addOwner(User $user): response
    {
        return $user->hasPermission('store.addOwner')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk menambah owner pada resto.');
    }

    public function viewOptions(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.viewOptions')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat opsi list resto.');
    }

    public function viewWithoutOwner(User $user): response
    {
        return $user->hasPermission('store.viewWithoutOwner')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat resto yang belum memiliki owner.');
    }

    public function viewOperatingHours(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.viewOperatingHours')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
    }

    public function updateOperatingHours(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.updateOperatingHours')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
    }

    public function updateStatus(User $user): response
    {
        return $user->hasPermission('store.updateStatus')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah status aktif resto.');
    }

    public function updateStatusOperational(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.updateStatusOperational')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
    }

    public function updateStatusOrder(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.updateStatusOrder')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
    }
}
