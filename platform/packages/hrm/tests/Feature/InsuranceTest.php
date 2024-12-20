<?php

use Tests\BaseAuthTest;
use Workable\HRM\Enums\InsuranceEnum;
use Workable\HRM\Models\Insurance;
use Workable\UserTenant\Models\User;

class InsuranceTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->member = User::query()->where('email', 'user_has_role_full_permission@gmail.com')->first();

        $this->data = [
            'user_id'            => $this->member->id,
            'number_insurance'   => '0681212121',
            'start_insurance'    => '2024-01-01',
            'date_closing'       => null,
            'sent_date'          => null,
            'return_date'        => null,
            'treatment_location' => 'Bệnh viện Đa khoa Xanh Pôn, 59 P. Trần Phú, Điện Biên, Ba Đình, Hà Nội, Việt Nam',
            'status'             => InsuranceEnum::STATUS_ACTIVE,
            'note'               => null,
        ];

        $this->item = Insurance::query()->create(array_merge($this->data, [
            'tenant_id' => get_tenant_id()
        ]));
    }

    public function test_create()
    {
        $response             = $this->json("POST", route('api.insurances.store'), $this->data);
        $this->data['status'] = InsuranceEnum::getStatus(InsuranceEnum::STATUS_ACTIVE);
        $response
            ->assertJsonFragment($this->data)
            ->assertStatus(200);
    }

    public function test_update()
    {
        $this->data['note']   = 'test update';
        $response             = $this->json("POST", route('api.insurances.update', $this->item->id), $this->data);
        $this->data['status'] = InsuranceEnum::getStatus(InsuranceEnum::STATUS_ACTIVE);
        $response
            ->assertJsonFragment($this->data)
            ->assertStatus(200);
    }

    public function test_show()
    {
        $response             = $this->json("GET", route('api.insurances.show', $this->item->id));
        $this->data['status'] = InsuranceEnum::getStatus(InsuranceEnum::STATUS_ACTIVE);

        $response
            ->assertJsonFragment($this->data)
            ->assertStatus(200);
    }

    public function test_delete()
    {
        $response = $this->json("DELETE", route('api.insurances.destroy', $this->item->id));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_index()
    {
        $response = $this->json("GET", route('api.insurances.index', $this->item->id), [
            'with' => 'tenant, user'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'insurances' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_list_paginated()
    {
        $response = $this->json("GET", route('api.insurances.index', $this->item->id), [
            'is_paginate' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'insurances' => [
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
                        'number_insurance',
                        'start_insurance',
                    ]
            ],
            [
                'data'           => ['user_id' => $this->member->id,],
                'expectedErrors' =>
                    [
                        'number_insurance',
                        'start_insurance',
                    ]
            ],

            [
                'data'           => ['number_insurance' => '0123123121'],
                'expectedErrors' =>
                    [
                        'user_id',
                        'start_insurance',
                    ]
            ],
            [
                'data'           => ['start_insurance' => date('Y-m-d H:i:s')],
                'expectedErrors' =>
                    [
                        'user_id',
                        'number_insurance',
                    ]
            ],

            // case sai dữ liệu
            [
                'data'           => [
                    'user_id'            => 'a',
                    'number_insurance'   => 1,
                    'start_insurance'    => 'a',
                    'date_closing'       => 'a',
                    'sent_date'          => 'a',
                    'return_date'        => 'a',
                    'treatment_location' => 1,
                    'status'             => 'a',
                    'note'               => 1,
                ],
                'expectedErrors' =>
                    [
                        'user_id',
                        'number_insurance',
                        'start_insurance',
                        'date_closing',
                        'sent_date',
                        'return_date',
                        'treatment_location',
                        'status',
                        'note',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", route('api.insurances.store'), $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
