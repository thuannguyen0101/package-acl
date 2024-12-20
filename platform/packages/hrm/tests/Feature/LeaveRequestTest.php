<?php

use Carbon\Carbon;
use Tests\BaseAuthTest;
use Workable\HRM\Enums\LeaveRequestEnum;
use Workable\HRM\Models\LeaveRequest;

class LeaveRequestTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->data = array(
            'user_id'     => $this->user->id,
            'tenant_id'   => $this->user->tenant_id,
            'leave_type'  => LeaveRequestEnum::LEAVE_APPLICATION,
            'start_date'  => Carbon::parse('08:00')->format('Y-m-d H:i:s'),
            'end_date'    => Carbon::parse('12:00')->format('Y-m-d H:i:s'),
            'reason'      => "Em xin nghỉ do ốm.",
            'approved_by' => $this->user->id,
            'status'      => LeaveRequestEnum::PENDING,
        );

        $this->item = LeaveRequest::query()->create($this->data);
    }

    public function test_create()
    {
        $response                 = $this->json('POST', route('api.leave-request.store'), $this->data);
        $this->data['leave_type'] = LeaveRequestEnum::getType($this->data['leave_type']);
        $this->data['status']     = LeaveRequestEnum::getStatus($this->data['status']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_update()
    {
        $this->data['status'] = LeaveRequestEnum::APPROVED;
        $response             = $this->json('POST', route('api.leave-request.update', $this->item->id), $this->data);

        $this->data['leave_type'] = LeaveRequestEnum::getType($this->data['leave_type']);
        $this->data['status']     = LeaveRequestEnum::getStatus($this->data['status']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_delete()
    {
        $response = $this->json('DELETE', route('api.leave-request.destroy', $this->item->id));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_show()
    {
        $response = $this->json('GET', route('api.leave-request.show', $this->item->id));

        $this->data['leave_type'] = LeaveRequestEnum::getType($this->data['leave_type']);
        $this->data['status']     = LeaveRequestEnum::getStatus($this->data['status']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_index()
    {
        $response = $this->json('GET', route('api.leave-request.index'), [
            'with' => 'user, approvedBy, tenant',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'leave_requests' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_index_user()
    {
        $response = $this->json('GET', route('api.leave-request.get_user'), [
            'with' => 'user, approvedBy, tenant',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'leave_requests' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_list_paginated()
    {
        $response = $this->json("GET", route('api.leave-request.index', $this->item->id), [
            'is_paginate' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'leave_requests' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_failed_validate()
    {
        $testCases = [
            [
                'data'           => [],
                'expectedErrors' =>
                    [
                        'user_id',
                        'leave_type',
                        'start_date',
                        'end_date',
                        'reason',
                    ]
            ],
            [
                'data'           => ['user_id' => $this->data['user_id']],
                'expectedErrors' =>
                    [
                        'leave_type',
                        'start_date',
                        'end_date',
                        'reason',
                    ]
            ],

            [
                'data'           => ['leave_type' => $this->data['leave_type']],
                'expectedErrors' =>
                    [
                        'user_id',
                        'start_date',
                        'end_date',
                        'reason',
                    ]
            ],

            [
                'data'           => ['start_date' => $this->data['start_date']],
                'expectedErrors' =>
                    [
                        'user_id',
                        'leave_type',
                        'end_date',
                        'reason',
                    ]
            ],

            [
                'data'           => ['end_date' => $this->data['end_date']],
                'expectedErrors' =>
                    [
                        'user_id',
                        'leave_type',
                        'start_date',
                        'reason',
                    ]
            ],

            [
                'data'           => ['reason' => $this->data['reason']],
                'expectedErrors' =>
                    [
                        'user_id',
                        'leave_type',
                        'start_date',
                        'end_date',
                    ]
            ],

            // case sai dữ liệu
            [
                'data'           => [
                    'user_id'     => 'a',
                    'leave_type'  => 'a',
                    'start_date'  => 'a',
                    'end_date'    => 'a',
                    'reason'      => 1,
                    'status'      => 'a',
                    'approved_by' => 'a',
                ],
                'expectedErrors' =>
                    [
                        'user_id',
                        'leave_type',
                        'start_date',
                        'end_date',
                        'reason',
                        'status',
                        'approved_by',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", route('api.leave-request.store'), $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
