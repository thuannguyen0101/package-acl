<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    protected $tenantData = [];
    protected $user = null;
    protected $tenant = [];
    protected $storeUrl = null;
    protected $formatData = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        $this->user = User::query()->create([
            'username' => 'thuannn',
            'email'    => 'thuannn@gmail.com',
            'password' => Hash::make('password'),
            'phone'    => '0123456789',
            'address'  => 'Số 8 tôn thất thuyết, cầu giấy, hà nội'
        ]);

        $response = $this->postJson(route('api.auth.login'), [
            'username' => 'thuannn',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->token = $response->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $this->token);

        $this->tenantData = [
            'name'  => 'Test Tenant',
            'email' => 'testtenant@test.com',
            'phone' => '0123456789',
        ];

        $this->tenant = Tenant::create([
            'name'    => 'Test Tenant 01',
            'user_id' => $this->user,
            'email'   => 'testtenant01@test.com',
            'phone'   => '0103456789',
            'status'  => TenantEnum::STATUS_ACTIVE,
        ]);

        $this->storeUrl  = route('api.tenants.store');
        $this->updateUrl = route('api.tenants.update', $this->tenant->id);

        $this->formatData = [
            'id',
            'name',
            'email',
            'phone',
            'status',
            'address',
            'full_name',
            'description',
            'business_phone',
            'meta_attribute',
            'gender',
            'birthday',
            'citizen_id',
            'start_at',
            'expiry_at',
        ];
    }

    public function test_create_tenant()
    {
        $response = $this->postJson($this->storeUrl, $this->tenantData);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "tenant" => $this->formatData
                ]
            ]);
    }

    public function test_create_tenant_failed()
    {
        $this->testValidate($this->storeUrl);
    }

    public function test_update_tenant()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $data = [
            'name'  => 'Test Tenant',
            'email' => 'testtenant@test.com',
            'phone' => '0999999999',
        ];

        $response = $this->putJson($this->updateUrl, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "tenant" => $this->formatData
                ]
            ]);
    }

    public function test_update_tenant_failed()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $this->testValidate($this->updateUrl, "PUT");
    }

    public function test_update_tenant_not_found()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $response = $this->putJson(route('api.tenants.update', 10), $this->tenantData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => -1,
            ]);
    }

    public function test_delete_tenant()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $response = $this->deleteJson($this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    public function test_delete_tenant_not_found()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $response = $this->deleteJson(route('api.tenants.destroy', 10));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => -1,
                'data' => []
            ]);
    }

    public function test_list_tenants()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $response = $this->getJson(route('api.tenants.index'));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1,
            ])
            ->assertJsonStructure([
                'data' => [
                    'tenants' => [
                        '*' => [
                            'name',
                            'email',
                            'phone',
                            'status',
                            'address',
                            'gender',
                            'birthday',
                            'size',
                            'citizen_id',
                            'start_at',
                            'expiry_at'
                        ]
                    ]
                ]
            ]);
    }

    public function test_show_tenant()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $response = $this->getJson($this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1,
            ])->assertJsonStructure([
                'data' => [
                    "tenant" => $this->formatData
                ]
            ]);
    }

    public function test_show_tenant_not_found()
    {
        $this->user->update([
            'tenant_id' => $this->tenant->id,
        ]);
        $response = $this->getJson(route('api.tenants.show', 10));
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
                        'name',
                        'email',
                        'phone',
                    ]
            ],
            [
                'data'           => ['name' => 'Test Tenant'],
                'expectedErrors' =>
                    [
                        'email',
                        'phone',
                    ]
            ],
            [
                'data'           => ['email' => 'testtenant@test.com'],
                'expectedErrors' =>
                    [
                        'name',
                        'phone',
                    ]
            ],
            [
                'data'           => ['phone' => '0123456789'],
                'expectedErrors' =>
                    [
                        'email',
                        'name',
                    ]
            ],
            // case sai dữ liệu
            [
                'data'           => [
                    'name'  => '1',
                    'email' => '1',
                    'phone' => '1',
                ],
                'expectedErrors' =>
                    [
                        'name',
                        'email',
                        'phone'
                    ]
            ],
            [
                'data'           => [
                    'name'           => 1,
                    'email'          => 1,
                    'phone'          => 1,
                    'address'        => 1,
                    'full_name'      => 1,
                    'description'    => 1,
                    'business_phone' => 1,
                    'meta_attribute' => 1,
                    'gender'         => 'a',
                    'birthday'       => 'a',
                    'citizen_id'     => 1,
                ],
                'expectedErrors' =>
                    [
                        'name',
                        'email',
                        'phone',
                        'address',
                        'full_name',
                        'description',
                        'business_phone',
                        'meta_attribute',
                        'gender',
                        'birthday',
                        'citizen_id',
                    ]
            ]
        ];

        foreach ($testCases as $testCase) {
            $response = $this->json($method, $route, $testCase['data']);
            $response->assertStatus(422)
                ->assertJsonValidationErrors($testCase['expectedErrors']);
        }
    }
}
