<?php

namespace Workable\UserTenant;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class UserTenantServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this
            ->setBasePath(base_path('packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('user-tenant')
            ->loadMigrations()
            ->loadAndPublishTranslates()
            ->loadRoutes(['api']);
    }
}
