<?php

namespace Workable\ACL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Workable\ACL\Models\User;

class UsersPermsSeeder extends Seeder
{
    public function run()
    {
        $permissions = Permission::all()->pluck('id')->toArray();

        $userIds = DB::table('tenants')
            ->from('tenants as t')
            ->select('user_id')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->get()->pluck('user_id')->toArray();

        $users = User::query()->whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $user->syncPermissions($permissions);
        }
    }

    //  php artisan db:seed --class=Workable\\ACL\\Database\\Seeders\\UsersPermsSeeder
}
