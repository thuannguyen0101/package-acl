<?php

use Tests\BaseAuthTest;
use Workable\HRM\Enums\PenaltyRuleEnum;
use Workable\HRM\Models\Penalty;
use Workable\HRM\Models\PenaltyRule;

class PenaltyTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->rule = PenaltyRule::create([
            'tenant_id'        => $this->user->tenant_id,
            'rule_name'        => "Tiền phạt đi muộn.",
            'rule_description' => "Tiền phạt đi muộn.",
            'type'             => PenaltyRuleEnum::LATE,
            'config'           => json_encode([
                'name'  => 'Muộn 10p.',
                'value' => 10,
                'price' => 10000,
            ]),
            'status'           => PenaltyRuleEnum::STATUS_ACTIVE,
            'created_by'       => $this->user->id,
            'updated_by'       => $this->user->id,
        ]);

        $this->data = [
            'tenant_id'     => $this->user->tenant_id,
            'attendance_id' => null,
            'rule_id'       => $this->rule->id,
            'user_id'       => $this->user->id,
            'fine_type'     => PenaltyRuleEnum::LATE,
            'amount'        => 10000,
            'status'        => PenaltyRuleEnum::STATUS_ACTIVE,
            'note'          => "FPT Aptech là một trong những trung tâm đào tạo",
            'created_by'    => $this->user->id,
            'updated_by'    => $this->user->id,
        ];

        $this->item = Penalty::create($this->data);
    }

    public function test_create()
    {
        $response                = $this->json("POST", route('api.penalties.store'), $this->data);
        $this->data['fine_type'] = PenaltyRuleEnum::getType($this->data['fine_type']);
        $this->data['status']    = PenaltyRuleEnum::getStatus($this->data['status']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_update()
    {
        $this->data['note'] = "FPT Aptech là một trong những trung tâm đào tạo x2";
        $response           = $this->json("POST", route('api.penalties.update', $this->item->id), $this->data);

        $this->data['fine_type'] = PenaltyRuleEnum::getType($this->data['fine_type']);
        $this->data['status']    = PenaltyRuleEnum::getStatus($this->data['status']);

        $response->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_destroy()
    {
        $response = $this->json("DELETE", route('api.penalties.destroy', $this->item->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }

    public function test_show()
    {
        $response = $this->json("GET", route('api.penalties.show', $this->item->id));

        $this->data['fine_type'] = PenaltyRuleEnum::getType($this->data['fine_type']);
        $this->data['status']    = PenaltyRuleEnum::getStatus($this->data['status']);

        $response->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.penalties.index'), [
            'with' => "tenant, user, attendance, penaltyRule, createdBy, updatedBy"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'penalties' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_list_paginated()
    {
        $response = $this->json("GET", route('api.penalties.index', $this->item->id), [
            'is_paginate' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'penalties' => [
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
                        'rule_id',
                        'user_id',
                        'fine_type',
                        'amount',
                        'status',
                    ]
            ],
            [
                'data'           => ['rule_id' => $this->data['rule_id']],
                'expectedErrors' =>
                    [
                        'user_id',
                        'fine_type',
                        'amount',
                        'status',
                    ]
            ],

            [
                'data'           => ['user_id' => $this->data['user_id']],
                'expectedErrors' =>
                    [
                        'rule_id',
                        'fine_type',
                        'amount',
                        'status',
                    ]
            ],

            [
                'data'           => ['fine_type' => $this->data['fine_type']],
                'expectedErrors' =>
                    [
                        'rule_id',
                        'user_id',
                        'amount',
                        'status',
                    ]
            ],
            [
                'data'           => ['amount' => $this->data['amount']],
                'expectedErrors' =>
                    [
                        'rule_id',
                        'user_id',
                        'fine_type',
                        'status',
                    ]
            ],
            [
                'data'           => ['status' => $this->data['status']],
                'expectedErrors' =>
                    [
                        'rule_id',
                        'user_id',
                        'fine_type',
                        'amount',
                    ]
            ],


            // case sai dữ liệu
            [
                'data'           => [
                    'attendance_id' => 'a',
                    'rule_id'       => 'a',
                    'user_id'       => 'a',
                    'fine_type'     => 'a',
                    'amount'        => 'a',
                    'status'        => 'a',
                    'note'          => 1,
                ],
                'expectedErrors' =>
                    [
                        'attendance_id',
                        'rule_id',
                        'user_id',
                        'fine_type',
                        'amount',
                        'status',
                        'note',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", route('api.penalties.store'), $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
