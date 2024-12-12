<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $table = 'penalties';

    protected $fillable = [
        'tenant_id',
        'attendance_id',
        'rule_id',
        'user_id',
        'fine_type',
        'amount',
        'status',
        'note',
        'created_by',
        'updated_by',
    ];
}
