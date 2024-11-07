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
        $this->permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);
        $this->role       = Role::create([
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


    public function test_update_permission_assign_role_api()
    {
        $role = Role::create(['name' => 'User']);

        $response = $this->putJson(route('api.permission.update', [
            'id'         => $this->permission->id,
            'role_ids[]' => $role->id
        ]));

        $response->assertStatus(200);

        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $this->permission->id,
            'role_id'       => $role->id,
        ]);
    }

    public function test_show_permission_api()
    {
        $role       = Role::create(['name' => 'User']);
        $role->givePermissionTo($this->permission);

        $response = $this->getJson(route('api.permission.show', [
            'id' => $this->permission->id,
        ]));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'    => $this->permission->id,
            'name'  => $this->permission->name,
            'group' => $this->permission->group,
        ]);
    }

    public function test_list_failed_permissions_api()
    {
        $this->permission->delete();
        $response = $this->getJson(route('api.permission.index'));
        $response->assertJsonFragment([
            'code' => -1,
        ]);
    }

    public function test_update_failed_permission_assign_role_api()
    {
        $response = $this->putJson(route('api.permission.update', [
            'id'         => 1,
            'role_ids[]' => 1
        ]));

        $response->assertStatus(404);
    }

    public function test_show_failed_role_api()
    {
        $response = $this->getJson(route('api.permission.show', [
            'id' => 1,
        ]));

        $response->assertStatus(404);
    }
}
