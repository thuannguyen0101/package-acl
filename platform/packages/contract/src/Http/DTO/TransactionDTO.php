<?php

namespace Workable\Contract\Http\DTO;

use Workable\Contract\Enums\CRMContractEnum;
use Workable\Contract\Enums\TransactionEnum;
use Workable\Customers\DTO\CustomerDTO;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class TransactionDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'transaction';

    protected static function getAdditionalRelations(): array
    {
        return [
            'customer' => CustomerDTO::class,
            'contract' => CRMContractDTO::class,
        ];
    }

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'           => $item->id,
            'tenant_id'    => $item->tenant_id,
            'contract_id'  => $item->contract_id,
            'customer_id'  => $item->customer_id,
            'amount'       => $item->amount,
            'deductions'   => $item->deductions,
            'total_amount' => $item->total_amount,
            'status'       => TransactionEnum::getStatus($item->status),
            'created_by'   => $item->created_by,
            'updated_by'   => $item->updated_by,
            'created_at'   => CRMContractEnum::formatDate($item->created_at),
            'updated_at'   => CRMContractEnum::formatDate($item->updated_at),
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
