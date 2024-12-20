<?php

use Tests\BaseAuthTest;
use Workable\HRM\Enums\PenaltyRuleEnum;
use Workable\HRM\Models\PenaltyRule;

class PenaltyRuleTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->data = [
            'tenant_id'        => $this->user->tenant_id,
            'rule_name'        => "Tiền phạt đi muộn.",
            'rule_description' => "Tiền phạt đi muộn.",
            'type'             => PenaltyRuleEnum::LATE,
            'config'           => [
                'name'  => 'Muộn 10p.',
                'value' => 10,
                'price' => 10000,
            ],
            'status'           => PenaltyRuleEnum::STATUS_ACTIVE,
            'updated_by'       => $this->user->id,
            'created_by'       => $this->user->id,
        ];

        $this->item = PenaltyRule::create(array_merge($this->data, [
            'config' => json_encode($this->data['config']),
        ]));
    }

    public function test_create()
    {
        $response = $this->json("POST", route('api.penalty_rules.store'), $this->data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }

    public function test_update()
    {
        $this->data['rule_name'] = "Tiền phạt đi muộn x2.";
        $response                = $this->json("POST", route('api.penalty_rules.update', $this->item->id), $this->data);

        $this->data['status'] = PenaltyRuleEnum::getStatus($this->data['status']);
        $this->data['type']   = PenaltyRuleEnum::getType($this->data['type']);

        $response->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_show()
    {
        $response = $this->json("GET", route('api.penalty_rules.show', $this->item->id));

        $this->data['status'] = PenaltyRuleEnum::getStatus($this->data['status']);
        $this->data['type']   = PenaltyRuleEnum::getType($this->data['type']);

        $response->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_delete()
    {
        $response = $this->json("DELETE", route('api.penalty_rules.destroy', $this->item->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.penalty_rules.index'), [
            'with' => 'tenant, createdBy, updatedBy'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'penalty_rules' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_list_paginated()
    {
        $response = $this->json("GET", route('api.penalty_rules.index', $this->item->id), [
            'is_paginate' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'penalty_rules' => [
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
                        'rule_name',
                        'config',
                        'type',
                        'config.name',
                        'config.value',
                        'config.price',
                    ]
            ],
            [
                'data'           => ['rule_name' => $this->data['rule_name']],
                'expectedErrors' =>
                    [
                        'config',
                        'type',
                        'config.name',
                        'config.value',
                        'config.price',
                    ]
            ],

            [
                'data'           => ['config' => $this->data['config']],
                'expectedErrors' =>
                    [
                        'rule_name',
                        'type',
                    ]
            ],

            [
                'data'           => ['type' => $this->data['type']],
                'expectedErrors' =>
                    [
                        'rule_name',
                        'config',
                        'config.name',
                        'config.value',
                        'config.price',
                    ]
            ],

            // case sai dữ liệu
            [
                'data'           => [
                    'rule_name'        => 1,
                    'config'           => 1,
                    'type'             => 'a',
                    'config.name'      => 1,
                    'config.value'     => 'a',
                    'config.price'     => 'a',
                    'rule_description' => 1,
                    'status'           => 'a',
                ],
                'expectedErrors' =>
                    [
                        'rule_name',
                        'config',
                        'type',
                        'config.name',
                        'config.value',
                        'config.price',
                        'rule_description',
                        'status',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", route('api.penalty_rules.store'), $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
