<?php

use Tests\BaseAuthTest;
use Workable\HRM\Enums\PenaltyRuleEnum;
use Workable\HRM\Models\PenaltyRule;

class FineTest extends BaseAuthTest
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
            'tenant_id'     => $this->user->id,
            'attendance_id' => null,
            'rule_id'       => $this->rule->id,
            'user_id'       => $this->user->id,
            'created_id'    => $this->user->id,
            'updated_id'    => $this->user->id,
            'fine_type'     => $this->rule->type,

            'amount' => 10000,

            'status' => PenaltyRuleEnum::STATUS_ACTIVE,

            'note' => " ",
        ];
    }

    public function test_create()
    {

        $response = $this->json("POST", route('api.fines.store'), $this->data);
        dd($response->json());

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }
}
