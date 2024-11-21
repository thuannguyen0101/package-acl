<?php

namespace Workable\Budget\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'expense_categories';

    protected $fillable = [
        'tenant_id',
        'area_id',
        'area_source_id',
        'name',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];
}
