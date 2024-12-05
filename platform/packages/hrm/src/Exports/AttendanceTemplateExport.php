<?php

namespace Workable\HRM\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceTemplateExport implements FromArray, WithHeadings
{

    public function array(): array
    {
        $list = [];

        $users = User::query()
            ->select('name', 'id')
            ->where('tenant_id', get_tenant_id())->get();

        foreach ($users as $user) {
            $data = array_merge([
                'user_id' => $user->id,
                'name'    => $user->name
            ]);

            $list[] = $data;
        }

        return $list;
    }

    public function headings(): array
    {
        return [
            'Mã người dùng',          // user_id
            'Tên người dùng',         // name
            'Ngày',                   // date
            'Thời gian vào',          // check_in
            'Thời gian ra',           // check_out
            'Công việc',              // work
            'Ca làm việc',            // work_shift
            'Trạng thái chấm công',   // attendance_status
            'Đi muộn (phút)',         // late
            'Về sớm (phút)',          // early
            'Ghi chú',                // note
            'Làm thêm giờ (phút)',    // overtime
        ];
    }
}
