<?php

namespace Workable\HRM\Enums;

class LeaveRequestEnum
{
    const LEAVE_APPLICATION = 1; // Đơn xin nghỉ
    const ABSENT            = 2; // Vắng mặt
    const TRANSFER          = 3; // Chuyển công tác
    const RESIGNATION       = 4; // Thôi việc

    const LEAVE_TYPE = [
        self::LEAVE_APPLICATION => 'Đơn xin nghỉ',
        self::ABSENT            => 'Vắng mặt',
        self::TRANSFER          => 'Chuyển công tác',
        self::RESIGNATION       => 'Thôi việc',
    ];

    const PENDING  = 1; // 'Đang chờ duyệt'
    const APPROVED = 2; // 'Đã phê duyệt'
    const REJECTED = 3; // 'Đã từ chối'

    const LEAVE_STATUS = [
        self::PENDING  => 'Đang chờ duyệt',
        self::APPROVED => 'Đã phê duyệt',
        self::REJECTED => 'Đã từ chối',
    ];

    const LEAVE_TYPE_SUB = [
        self::LEAVE_APPLICATION => 'Đơn xin nghỉ',
        self::ABSENT            => 'Vắng mặt',
    ];

    const MORNING       = 1; // Sáng từ 08:00 đến 12:00
    const AFTERNOON     = 2;// Chiều từ 13:00 đến 17:00
    const FULL_DAY      = 3;// Cả ngày từ 08:00 đến 17:00
    const MULTIPLE_DAYS = 4; // Nghỉ nhiều ngày


    public static function getStatus($status = null): array
    {
        return self::getDataEnum($status, self::LEAVE_STATUS);
    }

    public static function getType($status = null): array
    {
        return self::getDataEnum($status, self::LEAVE_TYPE);
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
