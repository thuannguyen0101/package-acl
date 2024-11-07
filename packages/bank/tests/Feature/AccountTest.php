<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Workable\ACL\Models\UserApi;
use Workable\Bank\Enums\AccountEnum;
use Workable\Bank\Models\Account;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $user = null;
    protected $role = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserApi::create([
            'name'     => 'test case 1',
            'email'    => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $this->role = Role::create([
            'name'       => 'admin',
            'guard_name' => 'api'
        ]);
    }

    public function test_account_index_without_login()
    {
        $response = $this->getJson(route('api.account.index'));

        $response->assertStatus(401);
    }

    public function test_account_index_login_not_permissions()
    {
        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('api.account.index'));

        $response->assertStatus(403);
    }

    public function test_account_index()
    {
        $user       = clone $this->user;
        $permission = Permission::create([
            'name'  => 'index_account',
            'group' => 'account',
        ]);

        $this->user->assignRole('admin');

        $this->role->givePermissionTo($permission);

        $user->assignRole($this->role);
        $this->createAccount();

        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('api.account.index', [
            'with' => 'user'
        ]));

        $response->assertStatus(200);
    }

    public function test_account_create_without_login()
    {
        $response = $this->postJson(route('api.account.store'));

        $response->assertStatus(401);
    }

    public function test_account_create_login_not_permissions()
    {
        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('api.account.store'));

        $response->assertStatus(403);
    }

    public function test_account_create()
    {
        $permission = Permission::create([
            'name'  => 'create_account',
            'group' => 'account',
        ]);

        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');

        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('api.account.store'), [
            'account_type' => '1',
            'bank_name'    => '2',
            'branch_name'  => '1',
        ]);

        $response->assertStatus(201);
    }

    public function test_account_failed_create()
    {
        // setup data
        $permission = Permission::create([
            'name'  => 'create_account',
            'group' => 'account',
        ]);

        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');

        $this->testValidate(route('api.account.store'));
    }

    public function test_account_update_without_login()
    {
        $response = $this->putJson(route('api.account.update', ['id' => 1]));
        $response->assertStatus(401);
    }

    public function test_account_update_login_not_permissions()
    {
        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson(route('api.account.update', ['id' => 1]));

        $response->assertStatus(403);
    }

    public function test_account_update()
    {
        $permission = Permission::create([
            'name'  => 'edit_account',
            'group' => 'account',
        ]);

        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');

        $account = $this->createAccount();

        $data = [
            'account_type' => 1,
            'bank_name'    => 2,
            'branch_name'  => 2,
        ];

        $token    = $this->loginUser();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson(route('api.account.update', ['id' => $account->id]), $data);

        $response->assertStatus(200);
    }

    public function test_account_update_failed()
    {
        $permission = Permission::create([
            'name'  => 'edit_account',
            'group' => 'account',
        ]);

        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');
        $account = $this->createAccount();
        $this->testValidate(route('api.account.update', [$account->id]), "PUT");
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
        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson(route('api.account.destroy', ['id' => 1]));

        $response->assertStatus(403);
    }

    public function test_account_delete()
    {
        $permission = Permission::create([
            'name'  => 'delete_account',
            'group' => 'account',
        ]);
        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');

        $account  = $this->createAccount();
        $token    = $this->loginUser();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson(route('api.account.destroy', ['id' => $account->id]));
        $response->assertStatus(204);
    }

    public function test_account_delete_failed()
    {
        $permission = Permission::create([
            'name'  => 'delete_account',
            'group' => 'account',
        ]);
        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');

        $this->testNotFound(route('api.account.destroy', ['id' => 1]));
    }

    public function test_account_show_without_login()
    {
        $response = $this->getJson(route('api.account.show', ['id' => 1]));

        $response->assertStatus(401);
    }

    public function test_account_show_login_not_permissions()
    {
        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('api.account.show', ['id' => 1]));

        $response->assertStatus(403);
    }

    public function test_account_show()
    {
        $permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);
        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');

        $account = $this->createAccount();
        $token   = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('api.account.show', ['id' => $account->id]));

        $response->assertStatus(200);
    }

    public function test_account_show_failed()
    {
        $permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);
        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');

        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('api.account.show', ['id' => 1]));

        $response->assertStatus(404);
    }

    protected function loginUser()
    {
        $user     = $this->user;
        $response = $this->postJson(route('api.auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        return $response->json('data.token');
    }

    protected function createAccount(): Account
    {
        $randomString = '';

        for ($i = 0; $i < 14; $i++) {
            $randomString .= rand(0, 9);
        }
        return Account::create([
            'user_id'        => $this->user->id,
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
        $token     = $this->loginUser();
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
        $token = $this->loginUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json($method, $route, $data);

        $response->assertStatus(404);
    }
}
