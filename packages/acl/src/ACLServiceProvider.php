<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 2024/10/20 - 13:27
 */

namespace Workable\ACL;

use Illuminate\Support\ServiceProvider;
use Workable\ACL\Core\Traits\LoadAndPublishDataTrait;

class ACLServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/auth.php' => config_path('auth.php'),
        ], 'config');
        $this
            ->setNamespace('acl')
            ->loadAndPublishConfigurations([
                'jwt', 'permission', 'auth'
            ])
            ->loadMigrations()
            ->loadRoutes(['api']);

        config("acl:auth");
    }

    public function register()
    {

    }
}
