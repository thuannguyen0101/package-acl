<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 2024/10/20 - 13:27
 */

namespace Workable\Contract;

use Illuminate\Support\ServiceProvider;
use Workable\Base\Traits\LoadAndPublishDataTrait;

class ContractServiceProvider extends ServiceProvider{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setBasePath(base_path('platform/packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('contract')
            ->loadAndPublishConfigurations([
                'auth',
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAndPublishTranslates()
            ->loadRoutes(['api']);
    }
}
