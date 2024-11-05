<?php

namespace Workable\ACL\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class UserApi extends Authenticatable implements JWTSubject
{
    use HasRoles, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'name',
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
