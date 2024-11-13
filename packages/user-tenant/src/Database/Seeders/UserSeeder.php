<?php

namespace Workable\UserTenant\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Workable\UserTenant\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'username' => 'thuannn',
            'email' => 'thuannn@gmail.com',
            'password' =>Hash::make('password'),
            'phone'   => '0123456789',
            'address' => 'Số 8 tôn thất thuyết, cầu giấy, hà nội'
        ]);

        User::query()->create([
            'username' => 'thuannn01',
            'email' => 'thuannn01@gmail.com',
            'password' =>Hash::make('password'),
            'phone'   => '0123456799',
            'address' => 'Số 09 tôn thất thuyết, cầu giấy, hà nội'
        ]);
    }
    //  php artisan db:seed --class=Workable\\UserTenant\\Database\\Seeders\\UserSeeder
}
