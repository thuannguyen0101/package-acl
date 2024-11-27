<?php

namespace Workable\Translate\Database\Seeders;

use Workable\ACL\Database\Seeders\PermsTableSeeder;

class PermsSeeder extends PermsTableSeeder
{
    protected $path_config = 'budget/src/Database/Seeders/Data/budget_permission.php';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        parent::run();
    }
    //  php artisan db:seed --class=Workable\\Budget\\Database\\Seeders\\PermsSeeder
}
