<?php

namespace Workable\HRM\Http\DTO;

use Workable\HRM\Enums\PenaltyRuleEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class PenaltyDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'penalty';

    protected static function getAdditionalRelations(): array
    {
        return [
            'penaltyRule' => PenaltyRuleDTO::class,
            'attendance'  => AttendanceDTO::class,
        ];
    }

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'            => $item->id,
            'tenant_id'     => $item->tenant_id,
            'attendance_id' => $item->attendance_id,
            'rule_id'       => $item->rule_id,
            'user_id'       => $item->user_id,
            'fine_type'     => PenaltyRuleEnum::getType($item->fine_type),
            'status'        => PenaltyRuleEnum::getStatus($item->status),
            'amount'        => $item->amount,
            'note'          => $item->note,
            'created_by'    => $item->created_by,
            'updated_by'    => $item->updated_by,
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
