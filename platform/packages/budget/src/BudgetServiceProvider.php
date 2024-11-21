<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 2024/10/20 - 13:27
 */

namespace Workable\Budget;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class BudgetServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setBasePath(base_path('platform/packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('budget')
            ->loadAndPublishConfigurations([
                'auth'
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAndPublishTranslates()
            ->loadRoutes(['api']);
    }
}
