<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_list_permission_api()
    {
        Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);

        $response = $this->getJson(route('api.permission.index'));

        $response->assertStatus(200);

        $response->assertJsonFragment(['name' => 'view_account', 'group' => 'account']);
    }

    public function test_update_permission_assign_role_api()
    {
        $permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);

        $role = Role::create(['name' => 'User']);

        $response = $this->putJson(route('api.permission.update', [
            'id'         => $permission->id,
            'role_ids[]' => $role->id
        ]));
        $response->assertStatus(200);

        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permission->id,
            'role_id'       => $role->id,
        ]);
    }

    public function test_show_permission_api()
    {
        $permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);
        $role       = Role::create(['name' => 'User']);

        $role->givePermissionTo($permission);

        $response = $this->getJson(route('api.permission.show', [
            'id' => $permission->id,
        ]));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'    => $permission->id,
            'name'  => $permission->name,
            'group' => $permission->group,
            'roles' => [
                $role->id
            ]
        ]);
    }

    public function test_list_failed_permissions_api()
    {
        $response = $this->getJson(route('api.permission.index'));

        $response->assertStatus(204);
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
            'id'         => 1,
        ]));

        $response->assertStatus(404);
    }
}
