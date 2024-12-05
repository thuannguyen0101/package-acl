<?php

namespace Workable\HRM\Http\DTO;

use Workable\HRM\Enums\AttendanceEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class AttendanceDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'attendance';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'                => $item->id,
            'user_id'           => $item->user_id,
            'tenant_id'         => $item->tenant_id,
            'date'              => $item->date,
            'check_in'          => $item->check_in,
            'check_out'         => $item->check_out,
            'late'              => AttendanceEnum::convertMinute($item->late),
            'early'             => AttendanceEnum::convertMinute($item->early),
            'work'              => AttendanceEnum::getWork($item->work),
            'work_shift'        => AttendanceEnum::getShiftWork($item->work_shift),
            'attendance_status' => AttendanceEnum::getStatus($item->attendance_status),
            'overtime'          => $item->overtime,
            'note'              => $item->note,
            'approved_by'       => $item->approved_by,
            'created_at'        => AttendanceEnum::convertDate($item->created_at),
            'updated_at'        => AttendanceEnum::convertDate($item->updated_at)
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
