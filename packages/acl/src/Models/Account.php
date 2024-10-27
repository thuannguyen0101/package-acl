<?php

namespace Workable\ACL\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
