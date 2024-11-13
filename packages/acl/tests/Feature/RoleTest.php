<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected $role;
    protected $permission;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');

        $this->artisan('db:seed', ['--class' => 'Workable\\UserTenant\\Database\\Seeders\\UserSeeder']);
        $this->artisan('db:seed', ['--class' => 'Workable\\ACL\\Database\\Seeders\\PermsSeeder']);
        $this->artisan('db:seed', ['--class' => 'Workable\\ACL\\Database\\Seeders\\UsersPermsSeeder']);

        $response = $this->postJson(route('api.auth.login'), [
            'username' => 'thuannn',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->token = $response->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $this->token);

        $this->role = Role::create([
            'name' => 'SuperAdmin',
            'tenant_id' => 1
        ]);

        $this->permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);
    }

    public function test_list_role_api()
    {
        $response = $this->getJson(route('api.role.index'));

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id'   => $this->role->id,
            'name' => 'SuperAdmin'
        ]);
    }

    public function test_created_role_api()
    {
        $data = ['name' => 'SuperAdmin_2'];

        $response = $this->postJson(route('api.role.store'), $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "role" => [
                        "id",
                        "name",
                        "guard_name",
                        "permissions",
                    ]
                ]
            ]);

        $this->assertDatabaseHas('roles', $data);
    }

    public function test_create_role_and_add_permission()
    {
        $data = [
            'name' => 'SuperAdmin_2',
            'permission_ids' => [$this->permission->id]
        ];

        $response = $this->postJson(route('api.role.store'), $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "role" => [
                        "id",
                        "name",
                        "guard_name",
                        "permissions" => [
                            '*' => []
                        ],
                    ]
                ]
            ]);
    }

    public function test_create_role_failed_api()
    {
        $data = ['name' => ''];
        $response = $this->postJson(route('api.role.store'), $data);

        $response->assertStatus(422)
            ->assertJsonFragment(['code' => -1])
            ->assertJsonValidationErrors([
                'name',
            ]);

        $data = ['name' => 'SuperAdmin'];

        $response = $this->postJson(route('api.role.store'), $data);
        $response->assertStatus(422)
            ->assertJsonFragment(['code' => -1])
            ->assertJsonValidationErrors([
                'name',
            ]);
    }

    public function test_create_role_and_add_permission_failed_api()
    {
        $data = [
            'name' => 'SuperAdmin_2',
            'permission_ids' => [99]
        ];

        $response = $this->postJson(route('api.role.store'), $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['code' => -1]);
    }



    public function test_updated_role_api()
    {
        $data = [
            'name' => 'SuperAdmin_2',
        ];

        $response = $this->putJson(route('api.role.update', $this->role->id), $data);

        $response->assertStatus(200);

        $response->assertJsonFragment($data);

        $this->assertDatabaseHas('roles', $data);
    }

    public function test_updated_failed_role_api()
    {
        $data = [
            'name' => 'SuperAdmin_2',
        ];

        $response = $this->putJson(route('api.role.update', 99), $data);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code' => -1,
        ]);
    }

    public function test_updated_role_and_assign_permission_api()
    {
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


        $this->role->givePermissionTo([$this->permission->id, $create]);

        $data = [
            'name'           => 'SuperAdmin',
            'permission_ids' => [$create->id, $edit->id],
        ];

        $response = $this->putJson(route('api.role.update', $this->role->id), $data);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name'        => $data['name'],
            'permissions' => $data['permission_ids']
        ]);
    }

    public function test_updated_failed_role_and_assign_revoke_permission_api()
    {
        $edit = Permission::create([
            'name'  => 'edit_account',
            'group' => 'account',
        ]);

        $this->role->givePermissionTo([$this->permission, $edit]);

        $data = [
            'name'           => 'SuperAdmin',
            'permission_ids' => [$edit->id, 10],
        ];

        $response = $this->putJson(route('api.role.update', $this->role->id), $data);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code'  => -1,
        ]);

        $data['name'] = 'SuperAdmin_2';
        $response     = $this->putJson(route('api.role.update', 20), $data);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'code'  => -1,
        ]);
    }

    public function test_deleted_role_api()
    {
        $response = $this->deleteJson(route('api.role.destroy', $this->role->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', [
            'name' => 'SuperAdmin',
        ]);
    }
}
