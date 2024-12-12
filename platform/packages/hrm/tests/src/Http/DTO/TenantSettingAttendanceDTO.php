<?php

namespace Workable\HRM\Http\DTO;

use Workable\UserTenant\Http\DTO\BaseDTO;
use Workable\UserTenant\Http\DTO\DTOInterface;

class TenantSettingAttendanceDTO extends BaseDTO implements DtoInterface
{
    protected static $dataKey = 'tenant_setting';

    public static function processSinger($item, array $relations = []): array
    {
        $data = [
            'id'                 => $item->id,
            'tenant_id'          => $item->tenant_id,
            'setting_attendance' => json_decode($item->setting_attendance, true),
            'created_by'         => $item->created_by,
            'updated_by'         => $item->updated_by,
        ];

        return self::addDataWith($item, $data, $relations);
    }
}
