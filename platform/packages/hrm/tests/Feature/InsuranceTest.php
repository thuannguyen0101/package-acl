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
        $response = $this->json("POST", route('api.insurances.store'), $this->data);
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $this->data['note'] = 'test update';
        $response           = $this->json("POST", route('api.insurances.update', $this->item->id), $this->data);
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $response           = $this->json("GET", route('api.insurances.show', $this->item->id));
        $response->assertStatus(200);
    }

    public function test_index()
    {
        $response           = $this->json("GET", route('api.insurances.index', $this->item->id), [
            'is_paginate' => true,
        ]);

        $response->assertStatus(200);
    }

}
