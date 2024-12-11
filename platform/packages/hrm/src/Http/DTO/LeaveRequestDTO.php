<?php

namespace Workable\HRM\Http\DTO;

use Workable\HRM\Enums\LeaveRequestEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;
use Workable\UserTenant\Http\DTO\UserDTO;

class LeaveRequestDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'leave_request';

    protected static function getAdditionalRelations(): array
    {
        return [
            'approvedBy' => UserDTO::class,
        ];
    }

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'          => $item->id,
            'tenant_id'   => $item->tenant_id,
            'user_id'     => $item->user_id,
            'leave_type'  => LeaveRequestEnum::getType($item->leave_type),
            'start_date'  => $item->start_date,
            'end_date'    => $item->end_date,
            'reason'      => $item->reason,
            'status'      => LeaveRequestEnum::getStatus($item->status),
            'approved_by' => $item->approved_by,
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
