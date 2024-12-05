<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Workable\{UserTenant\Models\User as UserTenant};

class User extends UserTenant
{
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }
}
