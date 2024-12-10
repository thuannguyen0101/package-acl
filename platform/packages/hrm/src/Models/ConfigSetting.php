<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigSetting extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'config_settings';

    protected $fillable = [
        'tenant_id',
        'shift_start_time',
        'break_start_time',
        'break_end_time',
        'shift_end_time',
        'full_time_minimum_hours',
        'exclude_weekends',
        'half_day_weekends',
        'created_by',
        'updated_by',
    ];
}
