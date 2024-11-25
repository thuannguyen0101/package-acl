<?php

namespace Workable\Budget\Http\DTO;

use Workable\Budget\Enums\AccountMoneyEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class BudgetDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'budget';

    protected static function getAdditionalRelations(): array
    {
        return [
            'expenseCategory' => ExpenseCategoryDTO::class,
            'accountMoney'    => AccountMoneyDTO::class,
        ];
    }

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'                  => $item->id ?? null,
            'tenant_id'           => $item->tenant_id ?? null,
            'area_id'             => $item->area_id ?? null,
            'area_source_id'      => $item->area_source_id ?? null,
            'expense_category_id' => $item->expense_category_id ?? null,
            'account_money_id'    => $item->account_money_id ?? null,
            'name'                => $item->name ?? null,
            'description'         => $item->description ?? null,
            'money'               => $item->money ?? null,
            'meta_file'           => json_decode(($item->meta_file ?? null), true),
            'meta_content'        => json_decode(($item->meta_content ?? null), true),
            'created_at'          => AccountMoneyEnum::convertDate($item->created_at),
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
