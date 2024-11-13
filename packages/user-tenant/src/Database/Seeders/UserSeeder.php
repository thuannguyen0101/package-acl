<?php

namespace Workable\UserTenant\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Workable\UserTenant\Enums\TenantEnum;
use Workable\UserTenant\Models\Tenant;
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
        DB::table('users')->whereIn('username', ['thuannn', 'thuannn01'])->delete();
        DB::table('tenants')->whereIn('email', ['testtenant11@test.com', 'testtenant10@test.com'])->delete();

        $users = [
            [
                'username' => 'thuannn',
                'email'    => 'thuannn@gmail.com',
                'password' => Hash::make('password'),
                'phone'    => '0123456789',
                'address'  => 'Số 8 tôn thất thuyết, cầu giấy, hà nội'
            ],
            [
                'username' => 'thuannn01',
                'email'    => 'thuannn01@gmail.com',
                'password' => Hash::make('password'),
                'phone'    => '0123456799',
                'address'  => 'Số 09 tôn thất thuyết, cầu giấy, hà nội'
            ]
        ];

        $tenants = [
            [
                'name'      => 'Test Tenant 10',
                'email'     => 'testtenant10@test.com',
                'phone'     => '0112345678',
                'status'    => TenantEnum::STATUS_ACTIVE,
                'full_name'  => 'Công ty VNP GROUP'
            ],
            [
                'name'   => 'Test Tenant 11',
                'email'  => 'testtenant11@test.com',
                'phone'  => '0122345678',
                'status' => TenantEnum::STATUS_ACTIVE,
                'full_name'  => 'Công ty VNP GROUP'
            ],
        ];

        foreach ($users as $key => $user) {
            $newUser = User::query()
                ->updateOrCreate($user, $user);
            $tenant  = $tenants[$key];

            $tenant['user_id'] = $newUser->id;
            $newTenant         = Tenant::query()->updateOrCreate($tenant, $tenant);

            $newUser->update(['tenant_id' => $newTenant->id]);

        }
    }
    //  php artisan db:seed --class=Workable\\UserTenant\\Database\\Seeders\\UserSeeder
}
