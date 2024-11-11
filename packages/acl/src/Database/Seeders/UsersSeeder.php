<?php

namespace Workable\ACL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Workable\ACL\Enums\UserEnum;
use Workable\ACL\Models\UserApi;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $systemUser = [
            'thuannn',
            'hungpm',
            'thangpt'
        ];

        $permission = Permission::all()->pluck('id')->toArray();
        foreach ($systemUser as $user) {
            $user = UserApi::query()->updateOrCreate([
                'username' => $user,
                'email'    => "$user@system.com",
                'password' => Hash::make('password123'),
            ], [
                'username' => $user,
                'email'    => "$user@system.com",
                'password' => Hash::make('password123'),
            ]);
            $user->givePermissionTo($permission);
        }

    }

    //  php artisan db:seed --class=Workable\\ACL\\Database\\Seeders\\UsersSeeder
}
