<?php

namespace Workable\HRM\Http\DTO;

use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class ConfigSettingAttendanceDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'config_attendance';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'                      => $item->id,
            'tenant_id'               => $item->tenant_id,
            'shift_start_time'        => $item->shift_start_time,
            'break_start_time'        => $item->break_start_time,
            'break_end_time'          => $item->break_end_time,
            'shift_end_time'          => $item->shift_end_time,
            'full_time_minimum_hours' => $item->full_time_minimum_hours,
            'exclude_weekends'        => json_decode($item->exclude_weekends, true),
            'half_day_weekends'       => json_decode($item->half_day_weekends, true),
            'created_by'              => $item->created_by,
            'updated_by'              => $item->updated_by,
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
