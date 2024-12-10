<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $table = 'fines';

    protected $fillable = [
        'tenant_id',
        'attendance_id',
        'rule_id',
        'user_id',
        'created_id',
        'updated_id',
        'fine_type',
        'amount',
        'status',
        'note',
    ];
}
