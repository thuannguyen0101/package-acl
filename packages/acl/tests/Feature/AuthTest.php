<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create([
            'name' => 'SuperAdmin',
        ]);

        $this->permission = Permission::create([
            'name'  => 'view_account',
            'group' => 'account',
        ]);
    }
}
