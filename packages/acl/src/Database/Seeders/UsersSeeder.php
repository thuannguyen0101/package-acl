<?php

namespace Workable\ACL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Workable\ACL\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $systemUser = [
            'thuannn',
            'hungpm',
            'thangpt'
        ];

        $userOld = User::query()->whereIn('username', $systemUser)->pluck('id', 'username')->toArray();

        $permission = Permission::all()->pluck('id')->toArray();

        foreach ($systemUser as $username) {
            $user = null;
            if (isset($userOld[$username])) {
                $user = User::query()->find($userOld[$username]);

                $oldPermission = $user->permissions()->pluck('id')->toArray();
                $newPermission = array_diff($permission, $oldPermission);
            } else {
                $user          = User::query()->updateOrCreate([
                    'username' => $username,
                    'email'    => "$username@system.com",
                    'password' => Hash::make('password123'),
                ], [
                    'username' => $username,
                    'email'    => "$username@system.com",
                    'password' => Hash::make('password123'),
                ]);
                $newPermission = $permission;
            };
            app(PermissionRegistrar::class)->forgetCachedPermissions();


            $user->givePermissionTo($newPermission);
        }

    }

    //  php artisan db:seed --class=Workable\\ACL\\Database\\Seeders\\UsersSeeder
}
