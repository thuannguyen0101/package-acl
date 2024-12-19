<?php

namespace Workable\Contract\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Workable\Customers\Models\Customer;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

class CRMContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'crm_contracts';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'contract_name',
        'status',
        'start_date',
        'end_date',
        'payment',
        'payment_notes',
        'discount_total',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(CRMContractHistory::class, 'contract_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'contract_id', 'id');
    }
}
