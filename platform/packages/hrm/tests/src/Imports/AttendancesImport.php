<?php

namespace Workable\HRM\Imports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Workable\HRM\Enums\AttendanceEnum;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Models\Attendance;
use Workable\HRM\Services\AttendanceService;

class AttendancesImport implements ToArray, WithStartRow, WithValidation
{
    protected $result = [];

    public function array(array $array): array
    {
        $user       = get_user();
        $configTime = config('hrm.attendance_time');

        $dataInsert = [];

        foreach ($array as $row) {
            $row = $this->map($row);
            if ($row['error']) {
                $this->result = [
                    'status'  => ResponseEnum::CODE_UNPROCESSABLE_ENTITY,
                    'message' => "",
                    'data'    => $row['messages'],
                ];
                return $this->result;
            }

            $service      = new AttendanceService();
            $dataInsert[] = $service->setupData($user, $row['data'], $configTime);
        }

        $this->result = [
            'status'  => ResponseEnum::CODE_OK,
            'message' => "",
            'data'    => Attendance::query()->insert($dataInsert),
        ];

        return $this->result;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function map($row): array
    {
        $row[2] = $this->transformDate($row[2]);
        $row[3] = $this->transformDateTime($row[3]);
        $row[4] = $this->transformDateTime($row[4]);

        try {
            Validator::make($row, [
                '0'  => ['required', 'exists:users,id'], // user_id
                '1'  => ['nullable'], // user_id
                '2'  => ['required', 'date_format:Y-m-d'], // date
                '3'  => ['required', 'date_format:H:i:s'], // check_in
                '4'  => ['required', 'date_format:H:i:s', 'after:3'], // check_out
                '5'  => ['required', 'numeric', Rule::in(array_keys(AttendanceEnum::WORK_TEXT))], // work
                '6'  => ['required', 'integer', Rule::in(array_keys(AttendanceEnum::SHIFT_TEXT))], // work_shift
                '7'  => ['required', 'integer', Rule::in(array_keys(AttendanceEnum::STATUS_TEXT))], // attendance_status
                '8'  => ['nullable', 'integer'], // late
                '9'  => ['nullable', 'integer'], // early
                '10' => ['nullable', 'string'], // note
                '11' => ['nullable', 'integer'], // overtime
            ])->validate();
        } catch (ValidationException $e) {
            return [
                'error'    => true,
                'messages' => $e->errors()
            ];
        }

        return [
            'error' => false,
            'data'  => [
                'user_id'           => (int)$row[0] ?? null,
                'date'              => $row[2],
                'check_in'          => $row[3],
                'check_out'         => $row[4],
                'work'              => $row[5],
                'work_shift'        => (int)$row[6],
                'attendance_status' => (int)$row[7],
                'late'              => (int)$row[8],
                'early'             => (int)$row[9],
                'note'              => $row[10],
                'overtime'          => $row[11],
            ]
        ];
    }

    public function rules(): array
    {
        return [];
    }

    private function transformDate($value): string
    {
        if (is_numeric($value)) {
            return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
        }
        return $value;
    }

    private function transformDateTime($value): string
    {
        if (is_numeric($value)) {
            return Carbon::instance(Date::excelToDateTimeObject($value))->format('H:i:s');
        }
        return $value;
    }
}
