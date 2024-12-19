<?php

use Carbon\Carbon;
use Tests\BaseAuthTest;
use Workable\Contract\Enums\CRMContractEnum;
use Workable\Contract\Enums\TransactionEnum;
use Workable\Contract\Models\CRMContract;
use Workable\Contract\Models\Transaction;
use Workable\Customers\Models\Customer;

class TransactionTest extends BaseAuthTest
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

        $this->contract = CRMContract::create([
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
            'updated_by'     => $this->user->id
        ]);

        $this->data = [
            'contract_id'  => $this->contract->id,
            'customer_id'  => $this->contract->customer_id,
            'amount'       => 1000000 / 2,
            'deductions'   => "Không",
            'total_amount' => 1000000 / 2,
            'created_by'   => $this->user->id,
            'status'       => TransactionEnum::STATUS_APPROVED
        ];

        $this->item = Transaction::create(array_merge($this->data, [
            'updated_by' => $this->user->id,
            'tenant_id'  => $this->user->tenant_id,
        ]));
    }

    public function test_create()
    {
        $response = $this->json("POST", route('api.transactions.store'), $this->data);

        $this->data['status'] = TransactionEnum::getStatus($this->data['status']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_update()
    {
        $this->data['amount']       = 1000000 / 2 + 25;
        $this->data['total_amount'] = 1000000 / 2 + 25;

        $response = $this->json("POST", route('api.transactions.update', $this->item->id), $this->data);

        $this->data['status'] = TransactionEnum::getStatus($this->data['status']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_show()
    {
        $response             = $this->json("GET", route('api.transactions.show', $this->item->id), $this->data);
        $this->data['status'] = TransactionEnum::getStatus($this->data['status']);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($this->data);
    }

    public function test_delete()
    {
        $response = $this->json("DELETE", route('api.transactions.destroy', $this->item->id));
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_list()
    {
        $response = $this->json("GET", route('api.transactions.index'));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'transactions' => [
                        '*' => array_keys($this->data)
                    ]
                ]
            ]);
    }

    public function test_list_paginated()
    {
        $response = $this->json("GET", route('api.transactions.index', $this->item->id), [
            'with'        => 'tenant, customer, contract,, createdBy, updatedBy',
            'is_paginate' => true,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'transactions' => [
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
                        'contract_id',
                        'customer_id',
                        'amount',
                        'deductions',
                        'total_amount',
                    ]
            ],
            [
                'data'           => ['contract_id' => $this->contract->id],
                'expectedErrors' =>
                    [
                        'customer_id',
                        'amount',
                        'deductions',
                        'total_amount',
                    ]
            ],

            [
                'data'           => ['customer_id' => $this->contract->customer_id],
                'expectedErrors' =>
                    [
                        'contract_id',
                        'amount',
                        'deductions',
                        'total_amount',
                    ]
            ],
            [
                'data'           => ['amount' => $this->contract->payment / 2],
                'expectedErrors' =>
                    [
                        'contract_id',
                        'customer_id',
                        'deductions',
                        'total_amount',
                    ]
            ],
            [
                'data'           => ['deductions' => "không"],
                'expectedErrors' =>
                    [
                        'contract_id',
                        'customer_id',
                        'amount',
                        'total_amount',
                    ]
            ],
            [
                'data'           => ['total_amount' => $this->contract->payment / 2],
                'expectedErrors' =>
                    [
                        'contract_id',
                        'customer_id',
                        'amount',
                        'deductions',
                    ]
            ],

            // case sai dữ liệu
            [
                'data'           => [
                    'contract_id'  => 'a',
                    'customer_id'  => 'a',
                    'amount'       => 'a',
                    'deductions'   => 1,
                    'total_amount' => 'a',
                    'status'       => 'a',
                    'created_by'   => 'a',
                ],
                'expectedErrors' =>
                    [
                        'contract_id',
                        'customer_id',
                        'amount',
                        'deductions',
                        'total_amount',
                        'status',
                        'created_by',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json("POST", route('api.transactions.store'), $testCase['data']);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => $testCase['expectedErrors']
                ]);
        }
    }
}
