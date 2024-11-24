<?php

namespace Workable\Budget\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

class AccountMoney extends Model
{
    use HasFactory;

    protected $table = 'account_monies';

    protected $fillable = [
        'tenant_id',
        'area_id',
        'area_source_id',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
