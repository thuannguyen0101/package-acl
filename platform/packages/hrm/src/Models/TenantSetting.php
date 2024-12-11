<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantSetting extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'tenant_settings';

    protected $fillable = [
        'tenant_id',
        'setting_attendance',
        'created_by',
        'updated_by',
    ];
}
