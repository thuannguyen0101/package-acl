<?php

namespace Workable\Navigation\Enums;

class CategoryMultiEnum
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
        return self::getDataEnum($status, self::STATUS, "");
    }

    public static function getDataEnum($field = null, $arrayEnum = null, $keyLang = null): array
    {
        $data = [
            'id'    => null,
            'value' => null
        ];

        if (isset($field)) {
            $data = [
                'id'    => $field,
                'value' => __("$keyLang." . $arrayEnum[$field])
            ];
        }

        return $data;
    }
}
