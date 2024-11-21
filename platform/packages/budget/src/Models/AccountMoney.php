<?php

namespace Workable\Budget\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
