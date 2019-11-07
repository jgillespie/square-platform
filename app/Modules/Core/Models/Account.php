<?php

namespace App\Modules\Core\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

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
        'is_enabled' => 'boolean',
        'is_backend' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The roles that belong to the account.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the account has a role.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasRole($name)
    {
        $result = $this->roles()->where('name', $name)->first();

        if (is_null($result)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the account has a permission.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasPermission($name)
    {
        $result = $this->roles()->whereHas('permissions', function ($query) use ($name) {
            $query->where('name', $name);
        })->first();

        if (is_null($result)) {
            return false;
        }

        return true;
    }
}
