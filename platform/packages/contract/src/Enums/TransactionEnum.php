<?php

namespace Workable\Contract\Enums;

class TransactionEnum
{
    const STATUS_PENDING  = 1; //  'pending';
    const STATUS_APPROVED = 2; //  'approved';
    const STATUS_CANCELED = 3; //  'canceled';

    const STATUS_TEXT = [
        self::STATUS_PENDING  => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_CANCELED => 'Canceled',
    ];

    public static function getStatus($status = null): array
    {
        return self::getDataEnum($status, self::STATUS_TEXT);
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
