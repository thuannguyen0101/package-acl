<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class BaseAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed', ['--class' => 'Workable\\UserTenant\\Database\\Seeders\\UserSeeder']);

        $this->user = User::query()->where('email', 'thuannn@gmail.com')->first();
    }

    protected function login()
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email'    => 'thuannn@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->token = $response->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $this->token);
    }
    protected function loginUserNotTenant()
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email'    => 'thuannn01@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->token = $response->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $this->token);
    }
}
