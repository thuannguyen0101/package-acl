<?php

namespace Workable\Contract\Http\DTO;

use Workable\Contract\Enums\CRMContractEnum;
use Workable\Customers\DTO\CustomerDTO;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class CRMContractDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'contract';

    protected static function getAdditionalRelations(): array
    {
        return [
            'customer'     => CustomerDTO::class,
            'histories'    => CRMContractHistoryDTO::class,
            'transactions' => TransactionDTO::class,
        ];
    }

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'             => $item->id ?? null,
            'tenant_id'      => $item->tenant_id ?? null,
            'customer_id'    => $item->customer_id ?? null,
            'contract_name'  => $item->contract_name ?? null,
            'status'         => CRMContractEnum::getStatus($item->status) ?? null,
            'start_date'     => $item->start_date ?? null,
            'end_date'       => $item->end_date ?? null,
            'payment'        => CRMContractEnum::formatPrice($item->payment) ?? null,
            'payment_notes'  => $item->payment_notes ?? null,
            'discount_total' => CRMContractEnum::formatPrice($item->discount_total) ?? null,
            'created_by'     => $item->created_by ?? null,
            'updated_by'     => $item->updated_by ?? null,
            'created_at'     => CRMContractEnum::formatDate($item->created_at),
            'updated_at'     => CRMContractEnum::formatDate($item->updated_at),
            'deleted_at'     => CRMContractEnum::formatDate($item->deleted_at ?? null),
            'transactions_sum_total_amount' => CRMContractEnum::formatPrice($item->transactions_sum_total_amount ?? null),
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
