<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermission('user.view')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat daftar data user.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $user->hasPermission('user.view')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data user ini.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermission('user.create')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat data user.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('user.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission('user.delete');
    }
}
