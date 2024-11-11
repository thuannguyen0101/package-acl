<?php

namespace Workable\UserTenant\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
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
}
