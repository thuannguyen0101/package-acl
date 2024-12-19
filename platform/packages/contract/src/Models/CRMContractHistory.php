<?php

namespace Workable\Contract\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMContractHistory extends Model
{
    use HasFactory;

    protected $table = 'crm_contract_histories';

    protected $fillable = [
        'tenant_id',
        'contract_id',
        'action',
        'meta_data',
        'created_by',
    ];
}
