<?php

namespace Workable\HRM\Enums;

class PenaltyRuleEnum
{
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_BLOCKED  = 3;

    const STATUS = [
        self::STATUS_ACTIVE   => 'active',
        self::STATUS_INACTIVE => 'inactive',
        self::STATUS_BLOCKED  => 'blocked',
    ];

    const LATE        = 1;
    const EARLY_LEAVE = 2;
    const BOTH        = 3;

    const TYPE = [
        self::LATE        => 'Late',
        self::EARLY_LEAVE => 'Early leave',
        self::BOTH        => 'Both',
    ];

    public static function getStatus($status = null): array
    {
        return self::getDataEnum($status, self::STATUS);
    }

    public static function getType($type = null): array
    {
        return self::getDataEnum($type, self::TYPE);
    }

    public static function getDataEnum($value = null, $arrayEnum = null): array
    {
        $data = [
            'id'    => null,
            'value' => null
        ];

        if (isset($value)) {
            $data = [
                'id'    => $value,
                'value' => $arrayEnum[$value]
            ];
        }

        return $data;
    }
}
