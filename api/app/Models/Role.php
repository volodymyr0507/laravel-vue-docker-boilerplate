<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    /**
     * Driver role type.
     *
     * @var string
     */
    public static $ROLE_DRIVER = 'driver';

    /**
     * Mechanic role type.
     *
     * @var string
     */
    public static $ROLE_MECHANIC = 'mechanic';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = [];
}
