<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $permission;
    protected $role;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');

        $this->permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);

        $this->role = Role::create([
            'name' => 'Admin',
        ]);
    }

    public function test_list_permission_api()
    {
        $response = $this->getJson(route('api.permission.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'permissions' => [
                        '*' => [
                            'id',
                            'name',
                            'group',
                            'guard_name'
                        ]
                    ]
                ]
            ]);

        $response->assertJsonFragment([
            'name'  => 'view_account',
            'group' => 'account'
        ]);
    }

    public function test_list_permission_api_filter_with()
    {
        $this->role->givePermissionTo($this->permission);

        $search = [
            'fields[roles]' => 'id,name',
            'with'          => 'roles'
        ];

        $response = $this->getJson(route('api.permission.index', $search));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'permissions' => [
                        '*' => [
                            'id',
                            'name',
                            'group',
                            'guard_name',
                            'roles' => [
                                '*' => [
                                    "id",
                                    "name",
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        $response->assertJsonFragment([
            'name'  => 'view_account',
            'group' => 'account'
        ]);
    }

    public function test_list_permissions_failed_api()
    {
        $this->permission->delete();
        $response = $this->getJson(route('api.permission.index'));

        $response->assertJsonStructure([
            'data' => []
        ]);
    }
}
