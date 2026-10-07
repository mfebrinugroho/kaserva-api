<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
        return $user->hasPermission('user.viewAny')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat daftar data User.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): response
    {
        return $user->hasPermission('user.view')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data user ini.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): response
    {
        return $user->hasPermission('user.create')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat data user.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): response
    {
        return $user->hasPermission('user.update')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah data user.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): response
    {
        return $user->hasPermission('user.delete')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah data user.');
    }
}
