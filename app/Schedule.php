<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Schedule extends Model implements AuthenticatableContract, AuthorizableContract
{

    use Authenticatable, Authorizable;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
