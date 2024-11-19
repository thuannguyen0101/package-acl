<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class BaseAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $token;
    protected $failPermission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'Workable\\Bank\\Database\\Seeders\\PermissionBankSeeder']);
        $this->artisan('db:seed', ['--class' => 'Workable\\UserTenant\\Database\\Seeders\\PermsSeeder']);
        $this->artisan('db:seed', ['--class' => 'Workable\\ACL\\Database\\Seeders\\PermsSeeder']);
        $this->artisan('db:seed', ['--class' => 'Workable\\UserTenant\\Database\\Seeders\\UserSeeder']);

        $this->user = User::query()->where('email', 'thuannn@gmail.com')->first();

        $this->failPermission = false;
    }

    protected function login(array $data = null)
    {
        $data = $data ?? [
            'email'    => 'thuannn@gmail.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('api.auth.login'), $data);

        $response->assertStatus(200);

        $this->token = $response->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $this->token);
    }

    protected function loginUserNotTenant()
    {
        $this->login([
            'email'    => 'thuannn01@gmail.com',
            'password' => 'password',
        ]);
    }

    protected function loginUserMemberHasPermission()
    {
        $this->login([
            'email'    => 'user_has_role_full_permission@gmail.com',
            'password' => 'password',
        ]);
    }

    protected function loginUserMemberNotPermission()
    {
        $this->login([
            'email'    => 'user_not_role_and_permission@gmail.com',
            'password' => 'password',
        ]);
    }
}
