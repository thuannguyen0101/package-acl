<?php

namespace Workable\Contract\Services;

use Workable\Contract\Enums\CRMContractHistoryEnum;
use Workable\Contract\Models\CRMContractHistory;

class CRMContractHistoryService
{
    public function store($item, int $action = CRMContractHistoryEnum::UPDATE, int $transactionId = null)
    {
        $data = [
            'contract_id'       => $item->id,
            'transaction_id' => $transactionId,
            'data'           => json_encode($item),
            'action'         => $action
        ];

        return CRMContractHistory::create($data);
    }
}
