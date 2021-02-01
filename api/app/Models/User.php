<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LaratrustUserTrait, Searchable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Checks if the given model can be searchable.
     *
     * @return boolean
     */
    public function shouldBeSearchable()
    {
        return $this->hasRole(Role::$ROLE_MECHANIC);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address->address,
            '_geoloc' => [
                'lat' => $this->address->latitude,
                'lng' => $this->address->longitude
            ]
        ];
    }

    /**
     * Get the Address record associated with the User.
     */
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the Services provided by the User.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the Jobs associated to a particular User.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the Bids made by a particular User.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Automatically hash the User's provided password.
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute(string $password) : void
    {
        if (trim($password) == '') {
            return;
        }

        $this->attributes['password'] = Hash::make($password);
    }
}
