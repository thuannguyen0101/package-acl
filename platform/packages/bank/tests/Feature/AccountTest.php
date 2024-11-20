<?php

use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Models\Account;
use Workable\UserTenant\Tests\BaseAuthTest;

class AccountTest extends BaseAuthTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'Workable\\Bank\\Database\\Seeders\\PermissionBankSeeder']);
        $this->artisan('db:seed', ['--class' => 'Workable\\UserTenant\\Database\\Seeders\\UserSeeder']);

        $this->login();

        $this->formatData = [
            'user_id',
            'tenant_id',
            'account_number',
            'balance',
            'account_type',
            'bank_name',
            'branch_name',
            'status',
        ];

        $this->data = [
            'account_type' => '1',
            'bank_name'    => '2',
            'branch_name'  => '1',
        ];

        $randomString = '';

        for ($i = 0; $i < 14; $i++) {
            $randomString .= rand(0, 9);
        }

        $this->account = Account::create([
            'user_id'        => $this->user->id,
            'tenant_id'      => $this->user->tenant_id,
            'account_number' => $randomString,
            'account_type'   => 1,
            'bank_name'      => 1,
            'branch_name'    => 1,
            'status'         => AccountEnum::STATUS_ACTIVE,
            'created_at'     => now()->format("Y-m-d H:i:s"),
            'updated_at'     => now()->format("Y-m-d H:i:s"),
        ]);
    }

    public function test_account_index()
    {

        $response = $this->json('GET', route('api.account.index', [
            'with' => 'user'
        ]));

        if ($this->failPermission) {
            $response->assertStatus(403);
            return;
        }

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "accounts" => [
                        '*' => $this->formatData
                    ]
                ]
            ]);

    }

    public function test_account_index_no_permission()
    {
        $this->failPermission = true;
        $this->loginUserMemberNotPermission();
        $this->test_account_index();
    }

    public function test_account_index_has_permission()
    {
        $this->loginUserMemberHasPermission();
        $this->test_account_index();
    }

    public function test_account_create()
    {
        $this->account->delete();

        $response = $this->json('POST', route('api.account.store'), $this->data);

        if ($this->failPermission) {
            $response->assertStatus(403);
            return;
        }

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                "account" => $this->formatData
            ]
        ]);
    }

    public function test_account_create_no_permission()
    {
        $this->failPermission = true;
        $this->loginUserMemberNotPermission();
        $this->test_account_create();
    }

    public function test_account_create_has_permission()
    {
        $this->loginUserMemberHasPermission();
        $this->test_account_create();
    }

    public function test_account_update()
    {
        $data = [
            'account_type' => 1,
            'bank_name'    => 2,
            'branch_name'  => 2,
        ];

        $response = $this->putJson(route('api.account.update', ['id' => $this->account->id]), $data);

        if ($this->failPermission) {
            $response->assertStatus(403);
            return;
        }

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "account" => $this->formatData
                ]
            ]);
    }

    public function test_account_update_no_permission()
    {
        $this->failPermission = true;
        $this->loginUserMemberNotPermission();
        $this->test_account_update();
    }

    public function test_account_update_has_permission()
    {
        $this->loginUserMemberHasPermission();
        $this->test_account_update();
    }

    public function test_account_delete()
    {
        $response = $this->deleteJson(route('api.account.destroy', ['id' => $this->account->id]));

        if ($this->failPermission) {
            $response->assertStatus(403);
            return;
        }

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);;
    }

    public function test_account_delete_no_permission()
    {
        $this->failPermission = true;
        $this->loginUserMemberNotPermission();
        $this->test_account_delete();
    }

    public function test_account_delete_has_permission()
    {
        $this->loginUserMemberHasPermission();
        $this->test_account_delete();
    }

    public function test_account_show()
    {
        $response = $this->getJson(route('api.account.show', ['id' => $this->account->id]));

        if ($this->failPermission) {
            $response->assertStatus(403);
            return;
        }

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "account" => $this->formatData
                ]
            ]);
    }

    public function test_account_show_no_permission()
    {
        $this->failPermission = true;
        $this->loginUserMemberNotPermission();
        $this->test_account_show();
    }

    public function test_account_show_has_permission()
    {
        $this->loginUserMemberHasPermission();
        $this->test_account_show();
    }

    protected function testValidate($route, $method = 'POST')
    {
        $token     = $this->tokenAdmin;
        $testCases = [
            [
                'data'           => [],
                'expectedErrors' =>
                    [
                        'account_type',
                        'bank_name',
                        'branch_name'
                    ]
            ],
            [
                'data'           => ['account_type' => 1],
                'expectedErrors' =>
                    [
                        'bank_name',
                        'branch_name'
                    ]
            ],
            [
                'data'           => ['bank_name' => 1],
                'expectedErrors' =>
                    [
                        'account_type',
                        'branch_name'
                    ]
            ],
            [
                'data'           => ['branch_name' => 1],
                'expectedErrors' =>
                    [
                        'account_type',
                        'bank_name',
                    ]
            ],
            // case sai dữ liệu
            [
                'data'           => [
                    'account_type' => 10,
                    'bank_name'    => 10,
                    'branch_name'  => 10,
                ],
                'expectedErrors' =>
                    [
                        'account_type',
                        'bank_name',
                        'branch_name'
                    ]
            ],

        ];

        foreach ($testCases as $testCase) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->json($method, $route, $testCase['data']);
            $response->assertStatus(422)
                ->assertJsonValidationErrors($testCase['expectedErrors']);
        }
    }
}
