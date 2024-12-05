<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'attendances';

    protected $fillable = [
        'id',
        'user_id',
        'tenant_id',
        'date',
        'check_in',
        'check_out',
        'work',
        'late',
        'early',
        'work_shift',
        'note',
        'overtime',
        'attendance_status',
        'approved_by',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
