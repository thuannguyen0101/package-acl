<?php

namespace Workable\ACL\Database\Seeders;

class PermsSeeder extends PermsTableSeeder
{
    protected $path_config = 'acl/src/Database/Seeders/Data/acl_permission.php';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        parent::run();
    }
    //  php artisan db:seed --class=Workable\\ACL\\Database\\Seeders\\PermsSeeder
}
