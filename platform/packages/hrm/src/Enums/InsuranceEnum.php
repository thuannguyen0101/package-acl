<?php

namespace Workable\HRM\Enums;

class InsuranceEnum
{
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_BLOCKED  = 3;

    const STATUS = [
        self::STATUS_ACTIVE   => 'active',
        self::STATUS_INACTIVE => 'inactive',
        self::STATUS_BLOCKED  => 'blocked',
    ];

    public static function getStatus($status = null): array
    {
        return self::getDataEnum($status, self::STATUS);
    }

    public static function getDataEnum($value = null, $arrayEnum = null): array
    {
        $data = [
            'value' => null,
            'text'  => null
        ];

        if (isset($value)) {
            $data = [
                'value' => $value,
                'text'  => $arrayEnum[$value]
            ];
        }

        return $data;
    }
}
