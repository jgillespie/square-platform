<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * The accounts that belong to the role.
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }
}
