<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can view a Job.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function show(User $user, Job $job)
    {
        if ($user->id !== $job->user->id
            && $user->hasRole(Role::$ROLE_DRIVER)
            && !$job->fulfilledJob()->exists()) {
            return false;
        }

        return true;
    }
}
