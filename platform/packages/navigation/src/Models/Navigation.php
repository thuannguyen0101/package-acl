<?php

namespace Workable\Navigation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

class Navigation extends Model
{
    use HasFactory;

    protected $table = 'navigations';

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
        'meta'
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
