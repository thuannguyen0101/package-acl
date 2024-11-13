<?php

namespace Workable\UserTenant\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $table = 'users';
    protected $fillable = [
        'tenant_id',
        'username',
        'password',
        'email',
        'phone',
        'status',
        'address',
        'gender',
        'birthday',
        'avatar',
        'created_by',
        'updated_by',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
