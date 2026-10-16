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

    public function viewAny(User $user): response
    {
        return $user->hasPermission('user.viewAny')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat daftar data User.');
    }

    public function view(User $user): response
    {
        return $user->hasPermission('user.view')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat data user ini.');
    }

    public function create(User $user): response
    {
        return $user->hasPermission('user.create')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat data user.');
    }

    public function update(User $user): response
    {
        return $user->hasPermission('user.update')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah data user.');
    }

    public function delete(User $user): response
    {
        return $user->hasPermission('user.delete')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah data user.');
    }

    public function switchStore(User $user): response
    {
        return $user->hasPermission('user.switchStore')
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengubah resto yang aktif.');
    }
}
