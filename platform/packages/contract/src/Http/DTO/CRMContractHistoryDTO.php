<?php

namespace Workable\Contract\Http\DTO;

use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class CRMContractHistoryDTO extends BaseDTO implements DtoInterface
{
    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'          => $item->id,
            'tenant_id'   => $item->tenant_id,
            'contract_id' => $item->contract_id,
            'action'      => $item->action,
            'meta_data'   => json_decode($item->meta_data),
            'created_by'  => $item->created_by,
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
