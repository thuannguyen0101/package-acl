<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 2024/10/20 - 13:27
 */

namespace Workable\ACL;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class ACLServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/auth.php' => config_path('auth.php'),
        ], 'config');

        $this->setBasePath(base_path('packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('acl')
            ->loadAndPublishConfigurations([
                'permission', 'auth'
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAndPublishTranslates()
            ->loadRoutes(['api']);

        config("acl:auth");
    }

    public function register()
    {

    }
}
