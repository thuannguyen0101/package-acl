<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenaltyRule extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'penalty_rules';

    protected $fillable = [
        'tenant_id',
        'rule_name',
        'rule_description',
        'type',
        'config',
        'status',
        'created_by',
        'updated_by',
    ];
}
