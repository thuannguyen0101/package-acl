<?php

namespace Workable\HRM\Enums;

use Carbon\Carbon;

class AttendanceEnum
{
    const LATE           = 1;     // Đi muộn
    const EARLY          = 2;     // Về sớm
    const ON_TIME        = 3;     // Đúng giờ
    const LATE_AND_EARLY = 4;     // Vừa đi muộn vừa về sớm

    const STATUS_TEXT = [
        self::LATE           => 'Late',
        self::EARLY          => 'Early',
        self::ON_TIME        => 'On time',
        self::LATE_AND_EARLY => 'Late and Early',
    ];

    const FULL_TIME = 1;
    const MORNING   = 2;
    const AFTERNOON = 3;

    const SHIFT_TEXT = [
        self::FULL_TIME => 'Full-time shift',
        self::MORNING   => 'Morning shift',
        self::AFTERNOON => 'Afternoon shift',
    ];

    const STANDARD_DAY = 2;
    const HALF_DAY     = 1;
    const WORK_TEXT    = [
        self::STANDARD_DAY => 'Full working day',
        self::HALF_DAY     => 'Half working day',
    ];


    public static function getStatuses(int $status, int $oldStatus = null): int
    {
        if ($oldStatus != null) {
            if ($status == self::EARLY && $oldStatus === self::LATE) {
                return self::LATE_AND_EARLY;
            }
        }

        return $status;
    }

    public static function getStatus($status = null): array
    {
        return self::getDataEnum($status, self::STATUS_TEXT);
    }

    public static function getWork($work = null): array
    {
        return self::getDataEnum($work, self::WORK_TEXT);
    }

    public static function getShiftWork($shiftWork = null): array
    {
        return self::getDataEnum($shiftWork, self::SHIFT_TEXT);
    }

    public static function convertMinute($minute): string
    {
        return $minute > 0 ? ($minute . 'p') : "$minute";
    }

    public function convertDate($date): ?string
    {
        return isset($date) ? Carbon::parse($date)->format('Y-m-d H:i:s') : null;
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
