<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'userimage'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getUserimageAttribute($value)
    {
        return url($value);
    }

    public function profiles()
    {
        return $this->hasOne(Profile::class);
    }

    public function tokenStudent()
    {
        return $this->hasOne(TokenStudent::class);
    }
}