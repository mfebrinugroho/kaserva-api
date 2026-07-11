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

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): response
    {
        return $user->hasPermission('store.view')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat daftar data resto/toko.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.view')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): response
    {
        if (!$user->hasPermission('store.update')) {
            return Response::deny('Anda tidak memiliki izin untuk melihat data resto/toko ini.');
        }

        return $user->stores()->whereKey($store->id)->exists()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengedit data resto/toko ini.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Store $store): bool
    {
        return false;
    }

    public function updateStatus(User $user, Store $store): bool
    {
        return false;
    }

    public function addOwner(User $user, Store $store): bool
    {
        return false;
    }
}
