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

        $this->item = Penalty::create([
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
        ]);

        $this->data = [
            'tenant_id'     => $this->user->tenant_id,
            'attendance_id' => null,
            'rule_id'       => $this->rule->id,
            'user_id'       => $this->user->id,
            'created_id'    => $this->user->id,
            'updated_id'    => $this->user->id,
            'fine_type'     => $this->rule->type,
            'amount'        => 10000,
            'status'        => PenaltyRuleEnum::STATUS_ACTIVE,
            'note'          => "FPT Aptech là một trong những trung tâm đào tạo",
        ];
    }

    public function test_create()
    {
        $response = $this->json("POST", route('api.penalties.store'), $this->data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }

    public function test_update()
    {
        $this->data['note'] = "FPT Aptech là một trong những trung tâm đào tạo x2";
        $response           = $this->json("POST", route('api.penalties.update', $this->item->id), $this->data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
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

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.penaltiesindex'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                "code" => 1
            ]);
    }
}
