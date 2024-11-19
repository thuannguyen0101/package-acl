<?php

namespace Workable\Bank\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Workable\ACL\Models\User;
use Workable\UserTenant\Models\Tenant;

class Account extends Model
{
    use SoftDeletes;

    protected $table = 'accounts';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'account_number',
        'balance',
        'account_type',
        'bank_name',
        'branch_name',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }
}
