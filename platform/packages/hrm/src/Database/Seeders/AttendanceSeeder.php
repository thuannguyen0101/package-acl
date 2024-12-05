<?php

namespace Workable\HRM\Database\Seeders;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Workable\HRM\Enums\AttendanceEnum;
use Workable\HRM\Models\Attendance;
use Workable\HRM\Services\AttendanceService;
use Workable\UserTenant\Models\User;

class AttendanceSeeder extends Seeder
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function run()
    {
        $startDate = Carbon::now()->subMonth()->firstOfMonth();
        $endDate   = $startDate->copy()->endOfMonth();
        $users     = User::query()
            ->select(['tenant_id', 'id'])
            ->whereIn('email', ['thuannn@gmail.com', 'user_has_role_full_permission@gmail.com'])
            ->get();

        $period     = CarbonPeriod::create($startDate, $endDate);
        $configTime = config('hrm.attendance_time');

        $data           = [
            'work'              => AttendanceEnum::STANDARD_DAY,
            'work_shift'        => AttendanceEnum::FULL_TIME,
            'attendance_status' => AttendanceEnum::ON_TIME,
        ];
        $dataItemInsert = [];

        foreach ($users as $user) {
            foreach ($period as $date) {
                if (!$date->isWeekend()) {
                    $start              = $date->addHours(8);
                    $end                = $date->copy()->addHours(9);
                    $data               = array_merge($data, [
                        'user_id'   => $user->id,
                        'date'      => $date->format('Y-m-d'),
                        'check_in'  => $start->format('H:i:s'),
                        'check_out' => $end->format('H:i:s'),
                    ]);
                    $item               = $this->attendanceService->setupData($user, $data, $configTime);
                    $item['created_at'] = $date->format('Y-m-d H:i:s');
                    $item['updated_at'] = $date->format('Y-m-d H:i:s');
                    $dataItemInsert[]   = $item;
                }
            }
        }


        Attendance::query()->insert($dataItemInsert);
    }
    // php artisan db:seed --class=Workable\\HRM\\Database\\Seeders\\AttendanceSeeder
}
