<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Workable\ACL\Models\User;
use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Models\Account;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $user = null;
    protected $admin = null;
    protected $account = null;
    protected $tokenUser = null;
    protected $tokenAdmin = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'Workable\\Bank\\Database\\Seeders\\PermissionBankSeeder']);

        $this->user = User::create([
            'name'     => 'test case 1',
            'email'    => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $this->admin = User::create([
            'name'     => 'test case 1',
            'email'    => 'test@admin.com',
            'password' => Hash::make('password'),
        ]);

        $this->role = Role::create([
            'name'       => 'admin',
            'guard_name' => 'api'
        ]);

        $this->role->givePermissionTo(1, 2, 3, 4, 5);
        $this->admin->assignRole($this->role);

        $this->tokenUser  = $this->loginUser($this->user->email);
        $this->tokenAdmin = $this->loginUser($this->admin->email);

        $this->account = $this->createAccount($this->admin);
    }

    public function test_account_index_without_login()
    {
        $response = $this->getJson(route('api.account.index'));

        $response->assertStatus(401);
    }

    public function test_account_index_login_not_permissions()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenUser,
        ])->json('GET', route('api.account.index'));

        $response->assertStatus(403);
    }

    public function test_account_index()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->json('GET', route('api.account.index', [
            'with' => 'user'
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "accounts" => [
                        '*' => [
                            "account_id",
                            "account_number",
                            "balance",
                            "account_type",
                            "bank_name",
                            "branch_name",
                            "status",
                            "user",
                        ]
                    ]
                ]
            ]);
    }

    public function test_account_create_without_login()
    {
        $response = $this->postJson(route('api.account.store'));

        $response->assertStatus(401);
    }

    public function test_account_create_login_not_permissions()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenUser,
        ])->json('POST', route('api.account.store'));

        $response->assertStatus(403);
    }

    public function test_account_create()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->json('POST', route('api.account.store'), [
            'account_type' => '1',
            'bank_name'    => '2',
            'branch_name'  => '1',
        ]);

        $response->assertStatus(200);
    }

    public function test_account_failed_create()
    {
        $this->testValidate(route('api.account.store'));
    }

    public function test_account_update_without_login()
    {
        $response = $this->putJson(route('api.account.update', ['id' => 1]));
        $response->assertStatus(401);
    }

    public function test_account_update_login_not_permissions()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenUser,
        ])->putJson(route('api.account.update', ['id' => 1]));

        $response->assertStatus(403);
    }

    public function test_account_update()
    {
        $data = [
            'account_type' => 1,
            'bank_name'    => 2,
            'branch_name'  => 2,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->putJson(route('api.account.update', ['id' =>  $this->account->id]), $data);

        $response->assertStatus(200);
    }

    public function test_account_update_failed()
    {
        $this->testValidate(route('api.account.update', [$this->account->id]), "PUT");

        $this->testNotFound(route('api.account.update', [99]), "PUT", [
            'account_type' => 1,
            'bank_name'    => 1,
            'branch_name'  => 1,
        ]);
    }

    public function test_account_delete_without_login()
    {
        $response = $this->deleteJson(route('api.account.destroy', ['id' => 1]));

        $response->assertStatus(401);
    }

    public function test_account_delete_login_not_permissions()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenUser,
        ])->deleteJson(route('api.account.destroy', ['id' => 1]));

        $response->assertStatus(403);
    }

    public function test_account_delete()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->deleteJson(route('api.account.destroy', ['id' => $this->account->id]));
        $response->assertStatus(200);
    }

    public function test_account_delete_failed()
    {
        $this->testNotFound(route('api.account.destroy', ['id' => 99]));
    }

    public function test_account_show_without_login()
    {
        $response = $this->getJson(route('api.account.show', ['id' => 1]));

        $response->assertStatus(401);
    }

    public function test_account_show_login_not_permissions()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenUser,
        ])->getJson(route('api.account.show', ['id' => 1]));

        $response->assertStatus(403);
    }

    public function test_account_show()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->getJson(route('api.account.show', ['id' => $this->account->id]));

        $response->assertStatus(200);
    }

    public function test_account_show_failed()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->getJson(route('api.account.show', ['id' => 2]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code'=> -1
            ]);
    }

    public function test_account_not_owned()
    {
        $this->account = $this->createAccount($this->user);
        $data = [
            'account_type' => 1,
            'bank_name'    => 2,
            'branch_name'  => 2,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->putJson(route('api.account.update', ['id' =>  $this->account->id]), $data);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code'=> -1
            ]);
    }

    protected function loginUser($email = null)
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email'    => $email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        return $response->json('data.token');
    }

    protected function createAccount($user = null): Account
    {
        $randomString = '';

        for ($i = 0; $i < 14; $i++) {
            $randomString .= rand(0, 9);
        }
        return Account::create([
            'user_id'        => $user->id,
            'account_number' => $randomString,
            'account_type'   => 1,
            'bank_name'      => 1,
            'branch_name'    => 1,
            'status'         => AccountEnum::STATUS_ACTIVE,
            'created_at'     => now()->format("Y-m-d H:i:s"),
            'updated_at'     => now()->format("Y-m-d H:i:s"),
        ]);
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

    protected function testNotFound($route, $method = 'DELETE', $data = [])
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin,
        ])->json($method, $route, $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code'=> -1
            ]);
    }
}
