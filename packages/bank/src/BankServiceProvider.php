<?php

namespace Workable\Bank;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class BankServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this
            ->setBasePath(base_path('packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('bank')
            ->loadMigrations()
            ->loadRoutes(['api']);
    }
}
