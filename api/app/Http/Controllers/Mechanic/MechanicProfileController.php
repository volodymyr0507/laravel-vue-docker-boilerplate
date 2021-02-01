<?php

namespace App\Http\Controllers\Mechanic;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\MechanicResource;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MechanicProfileController extends Controller
{
    /**
     * Get a Mechanics profile.
     *
     * @param \App\Models\User
     * @return App\Http\Resources\MechanicResource
     */
    public function __invoke(User $user)
    {
        if ($user->hasRole(Role::$ROLE_DRIVER)) {
            throw new ModelNotFoundException();
        }

        $this->authorize('showMechanicProfile', [User::class, $user]);

        return new MechanicResource($user->load('address'));
    }
}
