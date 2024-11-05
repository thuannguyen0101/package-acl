<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_read_list_role_api()
    {
        $role = Role::create([
            'name' => 'SuperAdmin',
        ]);

        $response = $this->getJson(route('api.role.index'));

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id'   => $role->id,
            'name' => 'SuperAdmin'
        ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
        ]);
    }

    public function test_created_role_api()
    {
        $data = [
            'name' => 'SuperAdmin',
        ];

        $response = $this->postJson(route('api.role.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('roles', $data);
    }

    public function test_created_failed_role_api()
    {
        $data = [];

        $response = $this->postJson(route('api.role.store'), $data);

        $response->assertStatus(422);

        $data['name'] = 'SuperAdmin';

        Role::create($data);

        $response = $this->postJson(route('api.role.store'), $data);
        $response->assertStatus(422);
    }

    public function test_created_role_and_assign_permission_api()
    {
        $view = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);

        $edit = Permission::create([
            'name'  => 'edit_account',
            'group' => 'account',
        ]);

        $data = [
            'name'           => 'SuperAdmin',
            'permission_ids' => [
                $view->id,
                $edit->id
            ],
        ];

        $response = $this->postJson(route('api.role.store'), $data);
        $response->assertStatus(201);

        $response->assertJsonFragment([
            'name'        => $data['name'],
            'permissions' => [
                $view->id,
                $edit->id
            ],
        ]);
    }

    public function test_updated_role_api()
    {
        $role = Role::create([
            'name' => 'SuperAdmin',
        ]);

        $data = [
            'name' => 'SuperAdmin_2',
        ];

        $response = $this->putJson(route('api.role.update', $role->id), $data);

        $response->assertStatus(200);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('roles', $data);
    }

    public function test_updated_failed_role_api()
    {
        $data = [
            'name' => 'SuperAdmin_2',
        ];

        $response = $this->putJson(route('api.role.update', 1), $data);
        $response->assertStatus(404);
        $response->assertJsonFragment([
            'status' => 'error',
        ]);
    }

    public function test_updated_role_and_assign_permission_api()
    {
        $view = Permission::create([
            //1
            'name'  => 'view_account',
            'group' => 'account',
        ]);

        $create = Permission::create([
            //2
            'name'  => 'create_account',
            'group' => 'account',
        ]);

        $edit = Permission::create([
            //3
            'name'  => 'edit_account',
            'group' => 'account',
        ]);

        $role = Role::create([
            'name' => 'SuperAdmin',
        ]);

        $role->givePermissionTo([$view, $create]);

        $data = [
            'name'           => 'SuperAdmin',
            'permission_ids' => [$create->id, $edit->id],
        ];

        $response = $this->putJson(route('api.role.update', $role->id), $data);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name'        => $data['name'],
            'permissions' => $data['permission_ids']
        ]);
    }

    public function test_updated_failed_role_and_assign_revoke_permission_api()
    {
        $role = Role::create([
            'name' => 'SuperAdmin',
        ]);

        $view = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);

        $edit = Permission::create([
            'name'  => 'edit_account',
            'group' => 'account',
        ]);

        $role->givePermissionTo([$view, $edit]);

        $data = [
            'name'           => 'SuperAdmin',
            'permission_ids' => [$edit->id, 10],
        ];

        $response = $this->putJson(route('api.role.update', $role->id), $data);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'status'  => 'error',
            'message' => 'Không tìm thấy quyền.',
        ]);

        $data['name'] = 'SuperAdmin_2';
        $response     = $this->putJson(route('api.role.update', 20), $data);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'status'  => 'error',
            'message' => 'Không tìm thấy vai trò.',
        ]);
    }

    public function test_deleted_role_api()
    {
        $role = Role::create([
            'name' => 'SuperAdmin',
        ]);

        $response = $this->deleteJson(route('api.role.destroy', $role->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('roles', [
            'name' => 'SuperAdmin',
        ]);
    }
}
