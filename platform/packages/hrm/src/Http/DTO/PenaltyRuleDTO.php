<?php

namespace Workable\HRM\Http\DTO;

use Workable\HRM\Enums\PenaltyRuleEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class PenaltyRuleDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'penalty_rule';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'               => $item->id,
            'tenant_id'        => $item->tenant_id,
            'rule_name'        => $item->rule_name,
            'rule_description' => $item->rule_description,
            'type'             => PenaltyRuleEnum::getType($item->type),
            'config'           => json_decode($item->config, true),
            'status'           => PenaltyRuleEnum::getStatus($item->status),
            'created_by'       => $item->created_by,
            'updated_by'       => $item->updated_by,
        ];

        return self::addDataWith($item, $data, $relations);
    }
}

