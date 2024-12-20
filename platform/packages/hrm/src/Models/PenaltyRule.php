<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
