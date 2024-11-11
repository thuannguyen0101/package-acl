<?php

namespace Workable\UserTenant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'address',
        'gender',
        'birthday',
        'size',
        'citizen_id',
        'start_at',
        'expiry_at'
    ];
}
