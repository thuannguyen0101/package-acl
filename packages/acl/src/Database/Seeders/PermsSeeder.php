<?php

namespace Workable\ACL\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Workable\ACL\Enums\UserEnum;

class PermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guard_name = 'api';

        $role_names = [
            UserEnum::ADMIN,
            UserEnum::USER,
        ];

        foreach ($role_names as $_name) {
            Role::findOrCreate($_name, $guard_name);
        }

        $group_perms = [
            'account' => [
                'index_account'  => [UserEnum::ADMIN],
                'create_account' => [UserEnum::USER],
                'view_account'   => [UserEnum::ADMIN, UserEnum::USER],
                'edit_account'   => [UserEnum::ADMIN, UserEnum::USER],
                'delete_account' => [UserEnum::ADMIN, UserEnum::USER],
            ]
        ];

        foreach ($group_perms as $group => $perms) {
            if (!$perms) {
                continue;
            }

            foreach ($perms as $perm => $role_ids) {
                $permission = Permission::query()
                    ->where('name', $perm)
                    ->where('guard_name', $guard_name)
                    ->first();

                if (!$permission) {
                    $permission = Permission::create([
                        'group'      => $group,
                        'name'       => $perm,
                        'guard_name' => $guard_name
                    ]);
                    $permission->syncRoles($role_ids);
                }
                if ($permission->group != $group) {
                    $permission->update([
                        'group' => $group,
                    ]);
                }
            }
        }
    }
    //  php artisan db:seed --class=Workable\\ACL\\Database\\Seeders\\PermsSeeder
}
