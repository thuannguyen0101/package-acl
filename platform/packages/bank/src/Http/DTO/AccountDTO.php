<?php

namespace Workable\Bank\Http\DTO;

use Workable\Bank\Enums\AccountEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class AccountDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'account';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'             => $item->id,
            'user_id'        => $item->user_id,
            'tenant_id'      => $item->tenant_id,
            'account_number' => $item->account_number,
            'balance'        => $item->balance,
            'account_type'   => AccountEnum::TYPE_TEXT[$item->account_type],
            'bank_name'      => AccountEnum::BANK_NAME_TEXT[$item->bank_name],
            'branch_name'    => AccountEnum::BRANCH_NAME_TEXT[$item->branch_name],
            'status'         => AccountEnum::STATUS_TEXT[$item->status],
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
