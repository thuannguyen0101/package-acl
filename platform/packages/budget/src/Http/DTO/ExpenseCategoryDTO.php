<?php

namespace Workable\Budget\Http\DTO;

use Workable\Budget\Enums\AccountMoneyEnum;
use Workable\Budget\Enums\ExpenseCategoryEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class ExpenseCategoryDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'expense_category';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'          => $item->id ?? null,
            'tenant_id'   => $item->tenant_id ?? null,
            'name'        => $item->name ?? null,
            'description' => $item->description ?? null,
            'status'      => ExpenseCategoryEnum::getStatus($item->status) ?? null,
            'created_at'  => AccountMoneyEnum::convertDate($item->created_at),
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
