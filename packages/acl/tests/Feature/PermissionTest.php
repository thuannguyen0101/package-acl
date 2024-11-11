<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Workable\ACL\Models\UserApi;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $permission;
    protected $role;
    protected $token = null;

    public function setUp(): void
    {
        parent::setUp();
        DB::setDefaultConnection('sqlite');

        $this->artisan('db:seed', ['--class' => 'Workable\\ACL\\Database\\Seeders\\PermsSeeder']);
        $this->artisan('migrate');

        $permission = Permission::all()->pluck('id')->toArray();

        $user = $this->admin = UserApi::create([
            'username' => 'thuannn',
            'email'    => 'thuannn@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user->givePermissionTo($permission);

        $response = $this->postJson(route('api.auth.login'), [
            'username' => $user->username,
            'password' => 'password',
        ]);
        $response->assertStatus(200);

        $this->token = $response->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $this->token);

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
