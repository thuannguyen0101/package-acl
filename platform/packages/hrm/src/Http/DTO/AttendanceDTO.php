<?php

namespace Workable\HRM\Http\DTO;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Workable\HRM\Enums\AttendanceEnum;
use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;
use Workable\UserTenant\Http\DTO\UserDTO;

class AttendanceDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'attendance';

    protected static function getAdditionalRelations(): array
    {
        return [
            'approvedBy' => UserDTO::class,
        ];
    }

    public static function processMultiple($items, array $relations = []): array
    {
        if (empty($items)) {
            return [];
        }

        $type = $relations['others']['type'] ?? null;

        $listItem = [];
        if ($type == 'month') {
            $startDate = isset($relations['others']['start_date'])
                ? Carbon::parse($relations['others']['start_date']) : Carbon::now()->startOfMonth();
            $endDate   = isset($relations['others']['start_end'])
                ? Carbon::parse($relations['others']['start_end']) : Carbon::now()->endOfMonth();
            $period    = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $date) {
                $listItem[$date->format('Y-m-d')] = null;
            }
        }
        foreach ($items as $item) {
            if ($type == 'month') {
                if (array_key_exists($item->date, $listItem)) {
                    $listItem[$item->date] = self::processSinger($item, $relations);
                }
            } elseif ($type == 'user') {
                if (!array_key_exists($item->user_id, $listItem)) {
                    $listItem[$item->user_id] = UserDTO::transform($item->user);
                }
                $listItem[$item->user_id]['attendances'][$item->date] = self::processSinger($item, $relations);
            } else {
                $listItem[] = self::processSinger($item, $relations);
            }
        }

        return $listItem;
    }

    public static function processSinger($item, array $relations = []): array
    {
        $data = [];

        if (!empty($relations['fields'])) {
            foreach ($relations['fields'] as $field) {
                $data[$field] = AttendanceEnum::getAttribute($field, $item->{$field});
            }
        } else {
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
        }

        return self::addDataWith($item, $data, $relations);
    }
}
