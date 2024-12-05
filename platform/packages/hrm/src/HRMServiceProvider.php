<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 2024/10/20 - 13:27
 */

namespace Workable\HRM;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class HRMServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setBasePath(base_path('platform/packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('hrm')
            ->loadAndPublishConfigurations([
                'auth', 'attendance_time'
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAndPublishTranslates()
            ->loadRoutes(['api']);
    }
}
