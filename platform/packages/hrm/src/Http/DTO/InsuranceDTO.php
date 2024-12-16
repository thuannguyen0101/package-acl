<?php

namespace Workable\HRM\Http\DTO;

use Workable\HRM\Enums\InsuranceEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class InsuranceDTO extends BaseDTO implements DtoInterface
{

    protected static $dataKey = 'insurance';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'                 => $item->id,
            'tenant_id'          => $item->tenant_id,
            'user_id'            => $item->user_id,
            'number_insurance'   => $item->number_insurance,
            'start_insurance'    => $item->start_insurance,
            'date_closing'       => $item->date_closing,
            'sent_date'          => $item->sent_date,
            'return_date'        => $item->return_date,
            'treatment_location' => $item->treatment_location,
            'status'             => InsuranceEnum::getStatus($item->status),
            'note'               => $item->note,
        ];

        return self::addDataWith($item, $data, $relations);
    }

}
