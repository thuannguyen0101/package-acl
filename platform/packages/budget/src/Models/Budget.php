<?php

namespace Workable\Budget\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

    protected $fillable = [
        'tenant_id',
        'area_id',
        'area_source_id',
        'name',
        'description',
        'expense_category_id',
        'account_money_id',
        'money',
        'meta_file',
        'meta_content',
        'created_by',
        'updated_by',
    ];
}
