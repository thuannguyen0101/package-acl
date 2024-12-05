<?php

namespace Workable\Navigation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

class CategoryMulti extends Model
{
    use HasFactory;

    protected $table = 'category_multi';

    protected $fillable = [
        'name',
        'root_id',
        'parent_id',
        'url',
        'type',
        'icon',
        'view_data',
        'label',
        'layout',
        'sort',
        'is_auth',
        'status',
        'meta',
        'created_by',
        'updated_by'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CategoryMulti::class, 'parent_id');
    }

    public function root(): BelongsTo
    {
        return $this->belongsTo(CategoryMulti::class, 'root_id');
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
