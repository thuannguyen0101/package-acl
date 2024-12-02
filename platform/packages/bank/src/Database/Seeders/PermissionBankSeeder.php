<?php

namespace Workable\Bank\Database\Seeders;

use Workable\ACL\Database\Seeders\PermsTableSeeder;

class PermissionBankSeeder extends PermsTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->path_config = __DIR__.'/../bank_permission.php';
        parent::run();
    }
    //  php artisan db:seed --class=Workable\\Bank\\Database\\Seeders\\PermissionBankSeeder
}
