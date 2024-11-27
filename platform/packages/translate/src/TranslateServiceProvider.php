<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 2024/10/20 - 13:27
 */

namespace Workable\Translate;



use Illuminate\Support\ServiceProvider;
use Workable\Base\Supports\Helper;
use Workable\Base\Traits\LoadAndPublishDataTrait;
use Workable\Translate\Commands\TranslateLanguageCommand;
use Workable\Translate\Services\TranslationService;

class TranslateServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->singleton(TranslationService::class, function ($app) {
            return new TranslationService();
        });
    }

    public function boot()
    {
        $this->commands([
            TranslateLanguageCommand::class,
        ]);

        $this->setBasePath(base_path('platform/packages' . DIRECTORY_SEPARATOR))
            ->setNamespace('translate')
            ->loadAndPublishConfigurations([
                'languages'
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadRoutes(['api']);

        Helper::autoload(base_path('platform/packages/translate/helpers'));
    }
}
