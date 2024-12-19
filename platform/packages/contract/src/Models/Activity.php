<?php

namespace Workable\Contract\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'type',
        'meta',
        'created_by',
    ];
}
