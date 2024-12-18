<?php

namespace Workable\Contract\Enums;

use Carbon\Carbon;

class CRMContractEnum
{
    const PENDING_APPROVAL = 1;     // Chờ phê duyệt
    const ACTIVE           = 2;     // Đang hoạt động
    const ON_HOLD          = 3;     // Tạm hoãn
    const EXPIRED          = 4;     // Hết hạn
    const TERMINATED       = 5;     // Chấm dứt
    const COMPLETED        = 6;     // Hoàn thành
    const CANCELLED        = 7;     // Hủy bỏ
    const STATUS_TEXT      = [
        self::PENDING_APPROVAL => 'Pending Approval',
        self::ACTIVE           => 'Active',
        self::ON_HOLD          => 'On Hold',
        self::EXPIRED          => 'Expired',
        self::TERMINATED       => 'Terminated',
        self::COMPLETED        => 'Completed',
        self::CANCELLED        => 'Cancelled',
    ];

    public static function getStatus($status = null): array
    {
        return self::getDataEnum($status, self::STATUS_TEXT);
    }

    public static function formatPrice($price): string
    {
        return number_format($price, 2, '.', ',');
    }

    public static function formatDate($date): ?string
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d H:i:s');
        }

        return $date ? date('Y-m-d H:i:s', $date) : null;
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
