<?php

use Carbon\Carbon;
use Tests\BaseAuthTest;
use Workable\HRM\Models\ConfigSetting;

class ConfigSettingAttendanceTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->data = [
            'shift_start_time'        => Carbon::parse('08:00')->format('H:i'),
            'break_start_time'        => Carbon::parse('12:00')->format('H:i'),
            'break_end_time'          => Carbon::parse('13:00')->format('H:i'),
            'shift_end_time'          => Carbon::parse('17:00')->format('H:i'),
            'full_time_minimum_hours' => 6,
            'exclude_weekends'        => [
                'sunday'   => true,
                'saturday' => true,
            ],
            'half_day_weekends'       => [
                'sunday' => true,
            ],
        ];

        $this->item = ConfigSetting::query()->create([
            'shift_start_time'        => Carbon::parse('08:10')->format('H:i'),
            'break_start_time'        => Carbon::parse('12:10')->format('H:i'),
            'break_end_time'          => Carbon::parse('13:10')->format('H:i'),
            'shift_end_time'          => Carbon::parse('17:10')->format('H:i'),
            'full_time_minimum_hours' => 6,
            'exclude_weekends'        => json_encode([
                'saturday' => true,
            ]),
            'half_day_weekends'       => json_encode([
                'sunday' => true,
            ]),
            'tenant_id'               => $this->user->tenant_id,
            'created_by'              => $this->user->id,
            'updated_by'              => $this->user->id,
        ]);


    }

    public function test_create()
    {
        $response = $this->json("POST", route('api.config.attendance.store'), $this->data);
        $response->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_update()
    {
        $this->data  ['break_end_time'] = Carbon::parse('13:30')->format('H:i');
        $response                       = $this->json("POST", route('api.config.attendance.update', $this->item->id), $this->data);

        $response->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_delete()
    {
        $response = $this->json("DELETE", route('api.config.attendance.destroy', $this->item->id));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_show()
    {
        $response = $this->json("GET", route('api.config.attendance.show', $this->item->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }
}
