<?php

namespace Workable\Bank\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Workable\ACL\Models\UserApi;

class Account extends Model
{
    use SoftDeletes;

    protected $table = 'accounts';

    protected $fillable = [
        'user_id',
        'account_number',
        'balance',
        'account_type',
        'bank_name',
        'branch_name',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserApi::class, 'user_id', 'id');
    }
}
