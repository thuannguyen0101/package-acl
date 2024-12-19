<?php

use Tests\BaseAuthTest;
use Workable\Contract\Enums\ActivityEnum;
use Workable\Contract\Models\Activity;
use Workable\Customers\Models\Customer;

class ActivityTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->customer = Customer::create([
            'tenant_id'      => $this->user->tenant_id,
            'name'           => 'Nguyễn Văn A',
            'id_number'      => '02131312312',
            'citizen_before' => '313123131312',
            'citizen_after'  => '3131312313',
            'address'        => '52 Đường Mỹ Đình, Mỹ Đình 2, Nam Từ Liêm, Hà Nội',
            'phone'          => '0213123111',
            'email'          => 'thuannn@gmail.com',
        ]);

        $this->data = [
            'customer_id' => $this->customer->id,
            'type'        => ActivityEnum::ADD_MEETING,
            'meta'        => [
                'title'     => "gọi cho người dùng",
                'direction' => "Gọi đi (Gọi cho khách)",
                'status'    => "Gọi được"
            ],
            'created_by'  => $this->user->id,
        ];

        $this->item = Activity::create(array_merge($this->data, [
            'tenant_id'  => $this->user->tenant_id,
            'created_by' => $this->user->id,
            'meta'       => json_encode($this->data['meta'])
        ]));
    }

    public function test_create()
    {
        $response = $this->json("POST", route('api.activities.store'), $this->data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_update()
    {
        $this->data['meta'] = [
            'title'     => "gọi cho người dùng",
            'direction' => "Gọi đi (Gọi cho khách)",
            'status'    => "Gọi không được"
        ];

        $response = $this->json("POST", route('api.activities.update', $this->item->id), $this->data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_show()
    {
        $response = $this->json("GET", route('api.activities.show', $this->item->id));

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_delete()
    {
        $response = $this->json("DELETE", route('api.activities.destroy', $this->item->id));
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.activities.index'));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'activities' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_list_paginated()
    {
        $response = $this->json("GET", route('api.activities.index', $this->item->id), [
            'is_paginate' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'activities' => [
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
                        'customer_id',
                        'type',
                        'meta',
                    ]
            ],
            [
                'data'           => ['customer_id' => $this->customer->id],
                'expectedErrors' =>
                    [
                        'type',
                        'meta',
                    ]
            ],

            [
                'data'           => ['type' => ActivityEnum::ADD_MEETING],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'meta',
                    ]
            ],
            [
                'data'           => [
                    'meta' => [
                        'title'     => "gọi cho người dùng",
                        'direction' => "Gọi đi (Gọi cho khách)",
                        'status'    => "Gọi được"
                    ]
                ],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'type',
                    ]
            ],

            // case sai dữ liệu
            [
                'data'           => [
                    'customer_id' => 'a',
                    'type'        => 'a',
                    'meta'        => 'a',
                ],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'type',
                        'meta',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", route('api.activities.store'), $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
