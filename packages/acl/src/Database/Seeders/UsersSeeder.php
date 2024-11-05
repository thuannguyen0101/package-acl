<?php

namespace Workable\ACL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Workable\ACL\Enums\UserEnum;
use Workable\ACL\Models\UserApi;

class UsersSeeder extends Seeder
{
    public function run()
    {
        UserApi::query()->truncate();

        UserApi::create([
            'name'     => 'Admin Thuannn',
            'email'    => 'thuannn@admin.com',
            'password' => Hash::make('password123'),
        ]);


        UserApi::create([
            'name'     => 'User Thuannn',
            'email'    => 'thuannn@user.com',
            'password' => Hash::make('password123'),
        ]);
    }
    //  php artisan db:seed --class=Workable\\ACL\\Database\\Seeders\\UsersSeeder
}
