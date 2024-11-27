<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 2024/10/20 - 13:27
 */

namespace Workable\Navigation;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class NavigationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setBasePath(base_path('platform/packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('navigation')
            ->loadAndPublishConfigurations([
                'auth'
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAndPublishTranslates()
            ->loadRoutes(['api']);
    }
}
