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

    const USER_PARTNER = 0;
    const USER_STUDENT = 1;
    const USER_COACH   = 2;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
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

    public function isPartner(){
        return $this->role_id == self::USER_PARTNER;
    }

    public function isStudent(){
        return $this->role_id == self::USER_STUDENT;
    }

    public function isCoach(){
        return $this->role_id == self::USER_COACH;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePartner($query)
    {
        return $query->where('role_id', self::USER_PARTNER);
    }
}