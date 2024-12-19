<?php

namespace Workable\Contract\Services;

use Workable\Contract\Enums\CRMContractHistoryEnum;
use Workable\Contract\Models\CRMContractHistory;

class CRMContractHistoryService
{
    public function store($item, int $action = CRMContractHistoryEnum::UPDATED)
    {
        $data = [
            'tenant_id'   => $item->tenant_id,
            'contract_id' => $item->id,
            'action'      => $action,
            'meta_data'   => json_encode($item),
            'created_by'  => get_user_id(),
        ];

        return CRMContractHistory::create($data);
    }
}
