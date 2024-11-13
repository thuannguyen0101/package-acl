<?php

namespace Workable\UserTenant;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Supports\Helper;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class UserTenantServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        Helper::autoload(__DIR__ . '/helpers');

        $this->publishes([
            __DIR__ . '/../config/auth.php' => config_path('auth.php'),
        ], 'config');

        $this
            ->setBasePath(base_path('packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('user-tenant')
            ->loadAndPublishConfigurations([
                'auth', 'jwt'
            ])
            ->loadMigrations()
            ->loadAndPublishTranslates()
            ->loadRoutes(['api']);

        config("user-tenant:auth");
    }
}
