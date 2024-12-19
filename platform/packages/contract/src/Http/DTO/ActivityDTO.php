<?php

namespace Workable\Contract\Http\DTO;

use Workable\Contract\Enums\CRMContractEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class ActivityDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'activity';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'          => $item->id ?? null,
            'tenant_id'   => $item->tenant_id ?? null,
            'customer_id' => $item->customer_id ?? null,
            'type'        => $item->type ?? null,
            'meta'        => json_decode($item->meta ?? null),
            'created_by'  => $item->created_by ?? null,
            'created_at'  => CRMContractEnum::formatDate($item->created_at) ?? null,
            'updated_at'  => CRMContractEnum::formatDate($item->updated_at) ?? null,
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
