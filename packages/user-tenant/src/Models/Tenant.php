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
        'user_id',
        'phone',
        'status',
        'address',
        'full_name',
        'description',
        'business_phone',
        'meta_attribute',
        'gender',
        'birthday',
        'size',
        'citizen_id',
        'start_at',
        'expiry_at'
    ];
}
