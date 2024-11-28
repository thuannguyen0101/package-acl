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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

    public function root(): BelongsTo
    {
        return $this->belongsTo(Navigation::class, 'root_id');
    }
}
