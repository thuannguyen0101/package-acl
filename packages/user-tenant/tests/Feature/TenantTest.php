<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Models\Tenant;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    protected $tenantData = [];
    protected $tenant = [];
    protected $storeUrl = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenantData = [
            'name'  => 'Test Tenant',
            'email' => 'testtenant@test.com',
            'phone' => '0123456789',
        ];

        $this->tenant = Tenant::create([
            'name'   => 'Test Tenant 01',
            'email'  => 'testtenant01@test.com',
            'phone'  => '0103456789',
            'status' => TenantEnum::STATUS_ACTIVE,
        ]);

//        $this->tenant = Tenant::create([
//            'name'   => 'Test Tenant 02',
//            'email'  => 'testtenant02@test.com',
//            'phone'  => '0203456789',
//            'status' => TenantEnum::STATUS_ACTIVE,
//        ]);

        $this->storeUrl  = route('api.tenants.store');
        $this->updateUrl = route('api.tenants.update', $this->tenant->id);
    }

    public function test_create_tenant()
    {
        $response = $this->postJson($this->storeUrl, $this->tenantData);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "tenant" => [
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
            ]);
    }

    public function test_create_tenant_failed()
    {
        $this->testValidate($this->storeUrl);
    }

    public function test_update_tenant()
    {
        $data = [
            'name'  => 'Test Tenant',
            'email' => 'testtenant@test.com',
            'phone' => '0999999999',
        ];

        $response = $this->putJson($this->updateUrl, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "tenant" => [
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
            ]);
    }

    public function test_update_tenant_failed()
    {
        $this->testValidate($this->updateUrl, "PUT");
    }

    public function test_update_tenant_not_found()
    {
        $response = $this->putJson(route('api.tenants.update', 10), $this->tenantData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => -1,
            ]);
    }

    public function test_delete_tenant()
    {
        $response = $this->deleteJson($this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    public function test_delete_tenant_not_found()
    {
        $response = $this->deleteJson(route('api.tenants.destroy', 10));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => -1,
                'data' => []
            ]);
    }

    public function test_list_tenants()
    {
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

    public function test_list_not_content_tenants()
    {
        $this->tenant->delete();
        $response = $this->getJson(route('api.tenants.index'));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1,
                'data' => []
            ]);

    }

    public function test_show_tenant()
    {
        $response = $this->getJson($this->updateUrl);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 1,
            ])->assertJsonStructure([
                'data' => [
                    "tenant" => [
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
            ]);
    }

    public function test_show_tenant_not_found()
    {
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
                    'name'       => '1',
                    'email'      => '1',
                    'phone'      => '1',
                    'address'    => 1,
                    'gender'     => 'male',
                    'birthday'   => 'female',
                    'size'       => 'test',
                    'citizen_id' => 1
                ],
                'expectedErrors' =>
                    [
                        'name',
                        'email',
                        'phone',
                        'address',
                        'gender',
                        'birthday',
                        'size',
                        'citizen_id'
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
