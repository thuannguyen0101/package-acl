<?php

namespace Workable\Budget\Database\Seeders;

use Workable\ACL\Database\Seeders\PermsTableSeeder;

class PermsSeeder extends PermsTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->path_config = __DIR__ . '/../budget_permission.php';
        parent::run();
    }
    //  php artisan db:seed --class=Workable\\Budget\\Database\\Seeders\\PermsSeeder
}
