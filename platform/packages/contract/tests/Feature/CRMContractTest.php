<?php

use Carbon\Carbon;
use Workable\Contract\Enums\CRMContractEnum;
use Workable\Contract\Models\CRMContract;
use Workable\Customers\Models\Customer;
use Workable\UserTenant\Tests\BaseAuthTest;

class CRMContractTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->customer = Customer::create([
            'tenant_id'      => $this->user->tenant_id,
            'name'           => 'thuannn',
            'id_number'      => '02131312312',
            'citizen_before' => '313123131312',
            'citizen_after'  => '3131312313',
            'address'        => '52 Đường Mỹ Đình, Mỹ Đình 2, Nam Từ Liêm, Hà Nội',
            'phone'          => '0213123111',
            'email'          => 'thuannn@gmail.com',
        ]);

        $this->data = [
            'tenant_id'      => $this->user->tenant_id,
            'customer_id'    => $this->customer->id,
            'contract_name'  => "Hợp đồng mua bán nhà.",
            'status'         => CRMContractEnum::PENDING_APPROVAL,
            'start_date'     => Carbon::now()->format('Y-m-d'),
            'end_date'       => Carbon::now()->addDay()->format('Y-m-d'),
            'payment'        => 1000000,
            'payment_notes'  => '52 Đường Mỹ Đình, Mỹ Đình 2, Nam Từ Liêm, Hà Nội',
            'discount_total' => 100000,
            'created_by'     => $this->user->id,
        ];

        $this->item = CRMContract::create(array_merge($this->data, [
            'tenant_id'  => $this->user->tenant_id,
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id
        ]));
    }

    public function test_create()
    {
        $response = $this->json("POST", route('api.contracts.store'), $this->data);

        $this->data['status']         = CRMContractEnum::getStatus($this->data['status']);
        $this->data['payment']        = CRMContractEnum::formatPrice($this->data['payment']);
        $this->data['discount_total'] = CRMContractEnum::formatPrice($this->data['discount_total']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_update()
    {
        $this->data['status'] = CRMContractEnum::ACTIVE;
        $response             = $this->json("POST", route('api.contracts.update', $this->item->id), $this->data);

        $this->data['status']         = CRMContractEnum::getStatus($this->data['status']);
        $this->data['payment']        = CRMContractEnum::formatPrice($this->data['payment']);
        $this->data['discount_total'] = CRMContractEnum::formatPrice($this->data['discount_total']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_show()
    {
        $response = $this->json("GET", route('api.contracts.show', $this->item->id), [
            'with' => 'tenant, customer,  createdBy, updatedBy'
        ]);

        $this->data['status']         = CRMContractEnum::getStatus($this->data['status']);
        $this->data['payment']        = CRMContractEnum::formatPrice($this->data['payment']);
        $this->data['discount_total'] = CRMContractEnum::formatPrice($this->data['discount_total']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_delete()
    {
        $response = $this->json("DELETE", route('api.contracts.destroy', $this->item->id));
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.contracts.index', $this->item->id));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'contracts' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_list_paginated()
    {
        $response = $this->json("GET", route('api.contracts.index', $this->item->id), [
            'with'        => 'tenant, customer,  createdBy, updatedBy',
            'is_paginate' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'contracts' => [
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
                        'contract_name',
                        'start_date',
                        'end_date',
                        'payment',
                    ]
            ],
            [
                'data'           => ['customer_id' => $this->customer->id],
                'expectedErrors' =>
                    [
                        'contract_name',
                        'start_date',
                        'end_date',
                        'payment',
                    ]
            ],

            [
                'data'           => ['contract_name' => 'Hợp đồng Test'],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'start_date',
                        'end_date',
                        'payment',
                    ]
            ],
            [
                'data'           => ['end_date' => date('Y-m-d H:i:s')],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'contract_name',
                        'start_date',
                        'payment',
                    ]
            ],
            [
                'data'           => ['payment' => 100000],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'contract_name',
                        'start_date',
                        'end_date',
                    ]
            ],

            // case sai dữ liệu
            [
                'data'           => [
                    'customer_id'    => '1',
                    'contract_name'  => 1,
                    'start_date'     => '1',
                    'end_date'       => '1',
                    'payment'        => 'a',
                    'status'         => 'a',
                    'payment_notes'  => 1,
                    'discount_total' => 'a',
                    'created_by'     => 'a',
                ],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'contract_name',
                        'start_date',
                        'end_date',
                        'payment',
                        'status',
                        'payment_notes',
                        'discount_total',
                        'created_by',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", route('api.contracts.store'), $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
