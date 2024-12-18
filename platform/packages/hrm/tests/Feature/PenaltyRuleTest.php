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
            'rule_name'        => "Tiền phạt đi muộn.",
            'rule_description' => "Tiền phạt đi muộn.",
            'type'             => PenaltyRuleEnum::LATE,
            'config'           => [
                'name'  => 'Muộn 10p.',
                'value' => 10,
                'price' => 10000,
            ],
            'status'           => PenaltyRuleEnum::STATUS_ACTIVE,
        ];

        $this->item = PenaltyRule::create([
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
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.penalty_rules.index'), $this->data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
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

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }

    public function test_delete()
    {
        $response = $this->json("DELETE", route('api.penalty_rules.destroy', $this->item->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }

    public function test_show()
    {
        $response = $this->json("GET", route('api.penalty_rules.show', $this->item->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }
}
