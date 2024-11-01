<?php

namespace Workable\ACL\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class UserApi extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $table = 'users';

//    public function __construct(array $attributes = [])
//    {
//        $this->table = config('acl.tables.auth_tokens_table');
//        parent::__construct($attributes);
//    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
