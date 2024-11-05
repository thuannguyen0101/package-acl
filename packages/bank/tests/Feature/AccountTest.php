<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Workable\ACL\Models\UserApi;

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

    public function test_account_index_with_login_not_permissions()
    {
        $user = clone $this->user;

        $response = $this->postJson(route('api.auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $token = $response->json('data.token');

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

        $response = $this->postJson(route('api.auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $token = $response->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('api.account.index'));

        $response->assertStatus(200);
    }

    public function test_account_create_without_login()
    {
        $response = $this->postJson(route('api.account.store'));

        $response->assertStatus(401);
    }

    public function test_account_create_without_login_not_permissions()
    {
        $user = clone $this->user;

        $response = $this->postJson(route('api.auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $token = $response->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('api.account.store'));

        $response->assertStatus(403);
    }

    public function test_account_create()
    {
        $user = clone $this->user;

        $permission = Permission::create([
            'name'  => 'create_account',
            'group' => 'account',
        ]);

        $this->role->givePermissionTo($permission);
        $this->user->assignRole('admin');


        $response = $this->postJson(route('api.auth.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);


        $response->assertStatus(200);

        $token = $response->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('api.account.store'), [
            'account_type' => '1',
            'bank_name'    => '2',
            'branch_name'  => '1',
        ]);

        $response->assertStatus(201);
    }

}
