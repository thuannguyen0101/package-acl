<?php

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\BaseAuthTest;
use Workable\HRM\Enums\AttendanceEnum;
use Workable\UserTenant\Models\User;

class AttendanceTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->member = User::query()->where('email', 'user_has_role_full_permission@gmail.com')->first();

        $this->halfDay = [
            "id"    => AttendanceEnum::HALF_DAY,
            "value" => AttendanceEnum::WORK_TEXT[AttendanceEnum::HALF_DAY],
        ];

        $this->workShiftMoning = [
            "id"    => AttendanceEnum::MORNING,
            "value" => AttendanceEnum::SHIFT_TEXT[AttendanceEnum::MORNING],
        ];

        $this->workShiftAfternoon = [
            "id"    => AttendanceEnum::AFTERNOON,
            "value" => AttendanceEnum::SHIFT_TEXT[AttendanceEnum::AFTERNOON],
        ];

        $this->attendanceStatusOnTime = [
            "id"    => AttendanceEnum::ON_TIME,
            "value" => AttendanceEnum::STATUS_TEXT[AttendanceEnum::ON_TIME],
        ];

        $this->attendanceStatusLateTime = [
            "id"    => AttendanceEnum::LATE,
            "value" => AttendanceEnum::STATUS_TEXT[AttendanceEnum::LATE],
        ];

        $this->data = [
            'user_id'           => $this->member->id,
            'date'              => Carbon::parse('2024:12:03 00:00:00')->format('Y-m-d'),
            'check_in'          => Carbon::parse('2024:12:03 08:10:00')->format('H:i:s'),
            'check_out'         => Carbon::parse('2024:12:03 12:00:00')->format('H:i:s'),
            'work'              => AttendanceEnum::HALF_DAY,
            'work_shift'        => AttendanceEnum::MORNING,
            'attendance_status' => AttendanceEnum::LATE,
            'late'              => null,
            'early'             => null,
            'note'              => null,
            'overtime'          => null,
        ];

        $this->artisan('db:seed', ['--class' => 'Workable\\HRM\\Database\\Seeders\\AttendanceSeeder']);
    }

    public function test_attendance()
    {
        $this->postAttendance(Carbon::parse('08:00:00')->format('Y-m-d H:i:s'));
        $response = $this->postAttendance(Carbon::parse('12:00:00')->format('Y-m-d H:i:s'));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'check_in'          => Carbon::parse('08:00:00')->format('H:i:s.0'),
                'check_out'         => Carbon::parse('12:00:00')->format('H:i:s.0'),
                "work"              => $this->halfDay,
                "work_shift"        => $this->workShiftMoning,
                "attendance_status" => $this->attendanceStatusOnTime,
            ]);
    }

    public function test_attendance_morning_late()
    {
        $this->postAttendance(Carbon::parse('08:10:00')->format('Y-m-d H:i:s'));
        $response = $this->postAttendance(Carbon::parse('12:00:00')->format('Y-m-d H:i:s'));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'check_in'          => Carbon::parse('08:10:00')->format('H:i:s.0'),
                'check_out'         => Carbon::parse('12:00:00')->format('H:i:s.0'),
                "work"              => $this->halfDay,
                "work_shift"        => $this->workShiftMoning,
                "attendance_status" => $this->attendanceStatusLateTime
            ]);
    }


    public function test_create_attendance()
    {
        $response = $this->json("POST", route('api.attendances.store'), $this->data);
        $response->assertStatus(200);
    }

    public function test_update_attendance()
    {
        $response = $this->json("POST", route('api.attendances.store'), $this->data);
        $id       = $response->json('data.attendance.id');

        $this->data['check_in'] = Carbon::parse('2024:12:03 08:00:00')->format('H:i:s');

        $response = $this->json("POST", route('api.attendances.update', $id), $this->data);

        $response->assertStatus(200);
    }

    public function test_show_attendance()
    {
        $response = $this->json("POST", route('api.attendances.store'), $this->data);
        $id       = $response->json('data.attendance.id');
        $response = $this->json("GET", route('api.attendances.show', $id), [
            'with' => 'user,tenant, approvedBy'
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_attendance()
    {
        $response = $this->json("POST", route('api.attendances.store'), $this->data);
        $id       = $response->json('data.attendance.id');
        $response = $this->json("DELETE", route('api.attendances.destroy', $id));

        $response->assertStatus(200);
    }

    public function test_show_attendance_by_month()
    {
        $response = $this->json("GET", route('api.attendances.user'), [
            'start_date' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'),
            'end_date'   => Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'),
            'type'       => 'month',
        ]);

        $response->assertStatus(200);
    }

    public function test_show_attendance_by_user()
    {
        $response = $this->json("GET", route('api.attendances.manager'), [
            'start_date' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'),
            'end_date'   => Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'),
            'type'       => 'user',
            'fields'     => ['id', 'work']
        ]);


        $response->assertStatus(200);
    }

    private function postAttendance($timestamp): TestResponse
    {
        return $this->json("POST", route('api.attendances.mark_attendance'), [
            'timestamp' => $timestamp,
        ]);
    }

    public function test_export_attendance_template()
    {
        $response = $this->json("GET", route('api.attendances.export.template'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $contentDisposition = $response->headers->get('Content-Disposition');

        // Kiểm tra xem phần tên file có đúng không (bỏ dấu ngoặc kép nếu có)
        $this->assertStringContainsString('attachment; filename=attendance_template.xlsx', $contentDisposition);
    }

    public function test_upload_attendance_template()
    {
        $filePath = base_path('platform/packages/hrm/files/test.xlsx');

        $file = new UploadedFile(
            $filePath,
            'test.xlsx', // Tên file
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // MIME type
            null, // Kích thước (Laravel tự tính)
            true  // Đánh dấu file hợp lệ
        );

        $response = $this->json('POST', route('api.attendances.import'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
    }
}
