<?php

namespace App\Policies;

use App\Exceptions\UserAuthorizationException;
use App\Models\User;
use Throwable;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     * @throws Throwable
     */
    public function viewAny(User $user): bool
    {
        if (!$user->hasPermissionTo('view.users') || !$user->hasRole('admin')) {
            throw new UserAuthorizationException('No tienes permiso para ver usuarios.');
        }

        return true;
    }

    /**
     * Determine whether the user can view the model.
     * @throws UserAuthorizationException
     */
    public function view(User $user, User $model): bool
    {
        if ($user->id !== $model->id && !$user->hasRole('admin')) {
            throw new UserAuthorizationException('No tienes permiso para ver el perfil de otro usuario.');
        }

        return true;
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
    public function update(User $user, User $model): bool
    {
        if ($user->id !== $model->id && !$user->hasRole('admin')) {
            throw new UserAuthorizationException('No tienes permiso para actualizar el perfil de otro usuario.');
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->id !== $model->id && !$user->hasRole('admin')) {
            throw new UserAuthorizationException('No tienes permiso para eliminar el perfil de otro usuario.');
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
