<?php

namespace Workable\HRM\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Workable\HRM\Enums\AttendanceEnum;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\DTO\AttendanceDTO;
use Workable\HRM\Models\Attendance;
use Workable\Support\Traits\FilterBuilderTrait;
use Workable\Support\Traits\ScopeRepositoryTrait;

class AttendanceService
{
    use FilterBuilderTrait, ScopeRepositoryTrait;

    public function index(array $request = []): array
    {
        $filters    = $this->getFilterRequest($request);
        $isPaginate = $request['is_paginate'] ?? false;
        $query      = $this->buildQuery($filters, is_admin($request));

        if ($isPaginate) {
            $budgets = $query->paginate(($request['per_page'] ?? 15));
        } else {
            $budgets = $query->get();
        }

        $budgets = AttendanceDTO::transform($budgets, $filters);

        return [
            'status'  => ResponseEnum::CODE_OK,
            'message' => __('budget::api.success'),
            'budgets' => $budgets
        ];
    }

    public function show(int $id, array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $attendance = $this->buildQuery($filters, is_admin($request))->find($id);

        if (!$attendance) {
            return $this->returnNotFound();
        }

        $attendance = AttendanceDTO::transform($attendance, $filters);

        return $this->returnSuccess($attendance);
    }

    public function markAttendance(array $request = []): array
    {
        $user             = get_user();
        $recordAttendance = $this->getRecordAttendanceNow($user);

        if ($recordAttendance) {
            $attendance = $this->punchOut($recordAttendance, $request);
        } else {
            $attendance = $this->punchIn($user, $request);
        }

        return [
            'status'     => ResponseEnum::CODE_OK,
            'message'    => "",
            'attendance' => AttendanceDTO::transform($attendance)
        ];
    }

    public function store(array $request = []): array
    {
        $user       = get_user();
        $configTime = config('hrm.attendance_time');
        $data       = $this->setupData($user, $request, $configTime);
        $attendance = Attendance::query()->create($data);

        return [
            'status'     => ResponseEnum::CODE_OK,
            'message'    => "",
            'attendance' => AttendanceDTO::transform($attendance)
        ];
    }

    public function update(int $id, array $request = []): array
    {
        $attendance = $this->findOne($id);
        $configTime = config('hrm.attendance_time');

        if (!$attendance) {
            $this->returnNotFound();
        }

        $user = $attendance->user;

        $data = $this->setupData($user, $request, $configTime);
        $attendance->fill($data);

        if ($attendance->isDirty()) {
            $attendance->update();
        }

        return [
            'status'     => ResponseEnum::CODE_OK,
            'message'    => "",
            'attendance' => AttendanceDTO::transform($attendance)
        ];
    }

    public function destroy(int $id): array
    {
        $attendance = $this->findOne($id);
        if (!$attendance) {
            $this->returnNotFound();
        }

        $attendance->delete();

        return [
            'status'     => ResponseEnum::CODE_OK,
            'message'    => "",
            'attendance' => $attendance
        ];
    }

    public function getUserAttendanceByMonth(array $request = []): array
    {
        $filters = $this->setupFilterDefaults($request);

        $filters['filter_base'][] = [
            'user_id',
            '=',
            get_user_id()
        ];
        $query                    = $this->buildQuery($filters, is_admin($request));
        $attendances              = $query->get();

        $attendances = AttendanceDTO::transform($attendances, $filters);

        return $this->returnSuccess($attendances, "");
    }

    public function getUsersAttendanceByMonth($request): array
    {
        $filters = $this->setupFilterDefaults($request);

        $query       = $this->buildQuery($filters, is_admin($request));
        $attendances = $query->get();
        $attendances = AttendanceDTO::transform($attendances, $filters);

        return [
            'status'      => ResponseEnum::CODE_OK,
            'message'     => "",
            'attendances' => $attendances
        ];
    }

    private function setupFilterDefaults(array $request = []): array
    {
        $filters = $this->getFilterRequest($request);

        $filters['others']['start_date'] = array_key_exists('start_date', $filters['others'])
            ? $filters['others']['start_date'] : Carbon::now()->startOfMonth()->format('Y-m-d');
        $filters['others']['end_date']   = array_key_exists('end_date', $filters['others'])
            ? $filters['others']['end_date'] : Carbon::now()->endOfMonth()->format('Y-m-d');

        $filters['filter_base'] = array_merge($filters['filter_base'], [
            ['date', '>=', $filters['others']['start_date']],
            ['date', '<=', $filters['others']['end_date']],
        ]);

        return $filters;
    }

    public function setupData($user, array $request, array $configTime): array
    {
        $breakStartTime = $configTime['break_start_time'];
        $breakEndTime   = $configTime['break_end_time'];
        $endTime        = $configTime['shift_end_time'];
        $startTime      = $configTime['shift_start_time'];

        $start = $request['check_in'];
        $end   = $request['check_out'];

        if ($this->getTimeCompare($breakEndTime, $end, true)) {
            $endTime = $breakStartTime;
        }
        if ($this->getTimeCompare($breakStartTime, $start)) {
            $startTime = $breakEndTime;
        }

        $late  = $this->getTimeCompare($startTime, $start);
        $early = $this->getTimeCompare($endTime, $end, true);

        return [
            'user_id'           => $request['user_id'],
            'tenant_id'         => $user->tenant_id,
            'date'              => $request['date'],
            'check_in'          => $request['check_in'],
            'check_out'         => $request['check_out'],
            'work'              => $request['work'],
            'work_shift'        => $request['work_shift'],
            'attendance_status' => $request['attendance_status'],

            'note'        => $request['note'] ?? null,
            'overtime'    => $request['overtime'] ?? null,
            'late'        => $request['late'] ?? $late,
            'early'       => $request['early'] ?? $early,
            'approved_by' => $user->id,
        ];

    }

    private function punchIn($user, array $request = [])
    {
        $day        = isset($request['timestamp']) ? Carbon::parse($request['timestamp']) : Carbon::now();
        $configTime = config('hrm.attendance_time');

        $breakStartTime = $configTime['break_start_time'];
        $startTime      = $configTime['shift_start_time'];
        $workShift      = AttendanceEnum::MORNING;

        // tg checkin có vượt qua thời gian kết thúc ca sáng không.
        if ($this->getTimeCompare($breakStartTime, $day)) {
            $startTime = $configTime['break_end_time'];
            $workShift = AttendanceEnum::AFTERNOON;
        }

        $late = $this->getTimeCompare($startTime, $day);

        $data = [
            'user_id'           => $user->id,
            'tenant_id'         => $user->tenant_id,
            'date'              => $day->format('Y-m-d'),
            'check_in'          => $day->format('H:i:s'),
            'late'              => $late,
            'work_shift'        => $workShift,
            'attendance_status' => $late ? AttendanceEnum::LATE : AttendanceEnum::ON_TIME,
        ];

        return Attendance::query()->create($data);
    }

    private function punchOut(Attendance $record, array $request = []): Attendance
    {
        $day            = isset($request['timestamp']) ? Carbon::parse($request['timestamp']) : Carbon::now();
        $configTime     = config('hrm.attendance_time');
        $breakStartTime = $configTime['break_start_time'];
        $breakEndTime   = $configTime['break_end_time'];
        $endTime        = $configTime['shift_end_time'];

        if ($this->getTimeCompare($breakEndTime, $day, true)) {
            $endTime = $breakStartTime;
        }

        $work  = $this->getWork($configTime, $record->check_in, $day);
        $early = $this->getTimeCompare($endTime, $day, true);

        $status = $early ? AttendanceEnum::EARLY : $record->attendance_status;
        $status = AttendanceEnum::getStatuses($status, $record->attendance_status);

        $record->update([
            'check_out'         => $day->format('H:i:s'),
            'work'              => $work,
            'early'             => $early,
            'work_shift'        => $work == AttendanceEnum::STANDARD_DAY ? AttendanceEnum::FULL_TIME : $record->work_shift,
            'attendance_status' => $status,
        ]);

        return $record->refresh();
    }

    private function getWork(array $config, $start, $end): int
    {
        $configStart = Carbon::createFromFormat('H:i', $config['break_start_time']);
        $configEnd   = Carbon::createFromFormat('H:i', $config['break_end_time']);
        $break_time  = $configStart->diffInHours($configEnd);

        $total_work_hours = Carbon::parse($start)->diffInHours($end) - $break_time;

        if ($total_work_hours > $config['full_time_minimum_hours']) {
            return AttendanceEnum::STANDARD_DAY;
        }
        return AttendanceEnum::HALF_DAY;
    }

    private function getTimeCompare($configStart, $timeNow, bool $isLT = false): int
    {
        if (!$timeNow instanceof Carbon) {
            $timeNow = Carbon::parse($timeNow);
        }

        $configStart  = Carbon::createFromFormat('H:i', $configStart);
        $diffInMinute = $configStart->diffInMinutes($timeNow);

        if ($isLT && $timeNow->lt($configStart)) {
            return $diffInMinute;
        }

        if (!$isLT && $timeNow->gt($configStart)) {
            return $diffInMinute;
        }

        return 0;
    }

    private function getRecordAttendanceNow($user): ?Attendance
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->where('tenant_id', $user->tenant_id)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->first();
    }

    private function buildQuery(array $filters = [], bool $isAdmin = false): Builder
    {
        $query = Attendance::query();

        if (!$isAdmin) {
            $query->where('tenant_id', get_tenant_id());
        }

        $this->scopeFilter($query, $filters['filter_base']);

        $this->scopeSort($query, $filters['orders']);

        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }

        return $query;
    }

    private function findOne(int $id)
    {
        return Attendance::query()->where('tenant_id', get_tenant_id())->find($id);
    }

    private function returnNotFound(): array
    {
        return [
            'status'  => ResponseEnum::CODE_NOT_FOUND,
            'message' => __('budget::api.not_found'),
            'budget'  => null
        ];
    }

    private function returnSuccess($attendance, string $message = ''): array
    {
        return [
            'status'     => ResponseEnum::CODE_OK,
            'message'    => $message ?: __('budget::api.success'),
            'attendance' => $attendance
        ];
    }
}
