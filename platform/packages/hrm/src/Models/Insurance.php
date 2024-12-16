<?php

namespace Workable\HRM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $table = 'insurances';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'number_insurance',
        'start_insurance',
        'date_closing',
        'sent_date',
        'return_date',
        'treatment_location',
        'status',
        'note'
    ];
}
