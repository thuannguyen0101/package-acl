<?php

namespace Workable\Bank\Database\Seeders;

use Workable\ACL\Database\Seeders\PermsTableSeeder;

class PermissionBankSeeder extends PermsTableSeeder
{
    protected $path_config = 'bank/src/Database/Seeders/Data/acl_permission.php';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        parent::run();
    }
    //  php artisan db:seed --class=Workable\\Bank\\Database\\Seeders\\PermissionBankSeeder
}
