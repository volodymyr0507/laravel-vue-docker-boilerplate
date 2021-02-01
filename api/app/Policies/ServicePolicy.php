<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can create services.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user, User $mechanic)
    {
        if ($user->id !== $mechanic->id
            && $user->hasRole(Role::$ROLE_MECHANIC)) {
            return false;
        }

        return true;
    }
}
