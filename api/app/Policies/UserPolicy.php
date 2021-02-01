<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can access a mechanics profile.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function showMechanicProfile(User $user, User $mechanic)
    {
        if ($user->id !== $mechanic->id
            && $user->hasRole(Role::$ROLE_MECHANIC)) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the given user can access a drivers profile.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function showDriverProfile(User $user, User $driver)
    {
        if ($user->id !== $driver->id
            && $user->hasRole(Role::$ROLE_DRIVER)) {
            return false;
        }

        return true;
    }
}
