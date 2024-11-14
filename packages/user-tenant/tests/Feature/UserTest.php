<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected $userData = [];
    protected $user = null;
    protected $storeUrl = null;
    protected $formatData = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');

        $this->user = User::create([
            'username' => 'thuannn',
            'email' => 'thuannn@gmail.com',
            'password' =>Hash::make('password'),
            'phone'   => '0123456789',
            'address' => 'Số 8 tôn thất thuyết, cầu giấy, hà nội'
        ]);

        $this->tenant = Tenant::create([
            'name'    => 'Test Tenant 01',
            'user_id' => 1,
            'email'   => 'testtenant01@test.com',
            'phone'   => '0103456789',
            'status'  => TenantEnum::STATUS_ACTIVE,
            'full_name'  => 'Công ty VNP GROUP'
        ]);

        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->postJson(route('api.auth.login'), [
            'login' => 'thuannn',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->token = $response->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $this->token);


        $this->userData = [
            'email'                 => 'email@email.com',
            'username'              => 'ThuanNguyen',
            'password'              => 'password',
            'phone'                 => '0123456799',
            'address'               => 'Số 8 Thất Thuyết, Cầu Giấy, Hà Nội.',
            'password_confirmation' => 'password'
        ];

        $this->storeUrl  = route('api.users.store');
        $this->updateUrl = route('api.users.update', $this->user->id);
        $this->formatData = [
            'id',
            'username',
            'email',
            'phone',
            'status',
            'address',
            'gender',
            'birthday',
            'avatar',
            'tenant',
            'created_by',
            'updated_by',
        ];
    }

    public function test_create_user()
    {
        $response = $this->postJson($this->storeUrl, $this->userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "user" => $this->formatData
                ]
            ]);

    }

    public function test_create_user_failed()
    {
        $this->testValidate(route('api.users.store'));
    }

    public function test_update_user()
    {
        $data     = [
            'email'    => 'email01@email.com',
            'username' => 'ThuanNguyen02',
            'phone'    => '0323456789',
            'address'  => 'Số 10 Thất Thuyết, Cầu Giấy, Hà Nội.',
        ];
        $response = $this->putJson($this->updateUrl, $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data)
            ->assertJsonStructure([
                'data' => [
                    "user" => [
                        'id',
                        'username',
                        'email',
                        'phone',
                        'status',
                        'address',
                        'gender',
                        'birthday',
                        'avatar',
                        'tenant',
                        'created_by',
                        'updated_by',
                    ]
                ]
            ]);
    }

    public function test_update_user_failed()
    {
        $this->testValidate(route('api.users.update', $this->user->id), "PUT");
    }

    public function test_update_user_not_found()
    {
        $response = $this->putJson(route('api.users.update', ['id' => -1]), $this->userData);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => -1
            ]);
    }

    public function test_delete_user()
    {
        $response = $this->deleteJson($this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ]);
    }

    public function test_delete_user_not_found()
    {
        $response = $this->deleteJson(route('api.users.destroy', ['id' => -1]));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => -1
            ]);
    }

    public function test_index_user()
    {
        $response = $this->getJson(route('api.users.index'));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1
            ])->assertJsonStructure([
                'data' => [
                    "users" => [
                        '*' => [
                            'id',
                            'username',
                            'email',
                            'phone',
                            'status',
                            'address',
                            'gender',
                            'birthday',
                            'avatar',
                            'tenant',
                            'created_by',
                            'updated_by',
                        ]
                    ]
                ]
            ]);
    }


    public function test_show_user()
    {
        $response = $this->getJson($this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1,
            ])->assertJsonStructure([
                'data' => [
                    "user" => [
                        'id',
                        'username',
                        'email',
                        'phone',
                        'status',
                        'address',
                        'gender',
                        'birthday',
                        'avatar',
                        'tenant',
                        'created_by',
                        'updated_by',
                    ]
                ]
            ]);
    }

    public function test_show_user_not_found()
    {
        $response = $this->getJson(route('api.users.show', 10));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => -1,
                'data' => []
            ]);
    }

    protected function testValidate($route, $method = 'POST')
    {
        $testCases = [
            [
                'data'           => [],
                'expectedErrors' =>
                    [
                        'email',
                        'username',
                        'password',
                        'phone',
                        'address',
                    ]
            ],
            [
                'data'           => ['username' => 'Test Tenant'],
                'expectedErrors' =>
                    [
                        'email',
                        'password',
                        'phone',
                        'address',
                    ]
            ],

            [
                'data'           => ['email' => 'testtenant@test.com'],
                'expectedErrors' =>
                    [
                        'username',
                        'password',
                        'phone',
                        'address',
                    ]
            ],
            [
                'data'           => ['phone' => '0123456789'],
                'expectedErrors' =>
                    [
                        'email',
                        'username',
                        'password',
                        'address',
                    ]
            ],
            [
                'data' => [
                    'password'              => 'password',
                    'password_confirmation' => 'password',
                ],

                'expectedErrors' =>
                    [
                        'email',
                        'username',
                        'phone',
                        'address',
                    ]
            ],
            [
                'data' => [
                    'address' => 'Số 8 Thất Thuyết, Cầu Giấy, Hà Nội.',
                ],

                'expectedErrors' =>
                    [
                        'email',
                        'username',
                        'password',
                        'phone',
                    ]
            ],

            // case sai dữ liệu
            [
                'data'           => [
                    'email'    => 1,
                    'username' => 1,
                    'password' => 1,
                    'phone'    => 1,
                    'address'  => 1,
                    'gender'   => 'a',
                    'birthday' => 'a',
                    'avatar'   => 1,
                ],
                'expectedErrors' =>
                    [
                        'email',
                        'username',
                        'password',
                        'phone',
                        'address',
                        'gender',
                        'birthday',
                        'avatar',
                    ]
            ],
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json($method, $route, $testCase['data']);

            if ($method == 'PUT') {
                $key = array_search('password', $testCase['expectedErrors']);
                unset($testCase['expectedErrors'][$key]);
            }

            $response->assertStatus(422)
                ->assertJsonValidationErrors($testCase['expectedErrors']);
        }
    }
}
