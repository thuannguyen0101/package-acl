<?php

namespace Workable\Navigation\Http\DTO;

use Workable\Budget\Enums\AccountMoneyEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class NavigationDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'account_money';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'          => $item->id ?? null,
            'tenant_id'   => $item->tenant_id ?? null,
            'name'        => $item->name ?? null,
            'description' => $item->description ?? null,
            'created_at'  => AccountMoneyEnum::convertDate($item->created_at),
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
