<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 9/6/19
 * Time: 19:32
 */

namespace Workable\ACL\Core\Traits;

use Illuminate\Support\Str;

trait LoadAndPublishDataTrait
{
    /**
     * @var string
     */
    protected $namespace = null;

    /**
     * @var string
     */
    protected $basePath = null;

    /**
     * @param $namespace
     * @return $this
     */
    public function setNamespace($namespace): self
    {
        $this->namespace = ltrim(rtrim($namespace, '/'), '/');
        return $this;
    }

    /**
     * @param $isInConsole
     * @return $this
     * @deprecated
     */
    public function setIsInConsole($isInConsole): self
    {
        return $this;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setBasePath($path): self
    {
        $this->basePath = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath ?? base_path('packages'. DIRECTORY_SEPARATOR);
    }

    /**
     * @return string
     */
    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }

    /**
     * @return string
     */
    protected function getDashedNamespace(): string
    {
        return str_replace('.', '/', $this->namespace);
    }

    protected function getConfigFilePath($file): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/config/' . $file . '.php';
    }

    public function getRouteFilePath($file)
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/routes/' . $file . '.php';
    }

    public function getViewsPath()
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/views/';
    }

    public function getTranslationsPath()
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/lang/';
    }

    public function getAssetsPath()
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/assets/';
    }

    public function getPublicPath()
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/public/';
    }

    public function getMigrationPath()
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/src/Database/Migrations';
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param $file_names
     * @return $this
     */
    public function loadAndPublishConfigurations($file_names): self
    {
        if (!is_array($file_names)) {
            $file_names = [$file_names];
        }
        foreach ($file_names as $file_name)
        {
            $this->mergeConfigFrom($this->getConfigFilePath($file_name), $this->getDotedNamespace() . '.' . $file_name);

            if ($this->app->runningInConsole())
            {
                $this->publishes([
                    $this->getConfigFilePath($file_name) => config_path($this->getDashedNamespace() . '/' . $file_name . '.php'),
                ], 'config');
            }
        }

        return $this;
    }


    public function loadBreadcrumb($file_names = 'breadcrumbs')
    {
        require_once($this->getRouteFilePath($file_names));
        return $this;
    }

    public function loadRoutes($file_names = ['web'])
    {
        if (!is_array($file_names)) {
            $file_names = [$file_names];
        }

        foreach ($file_names as $file_name) {
            $this->loadRoutesFrom($this->getRouteFilePath($file_name));
        }

        return $this;
    }

    public function loadMigrations()
    {
        $this->loadMigrationsFrom($this->getMigrationPath());
        return $this;
    }

    public function runningInConsoleCommand()
    {
        return $this->app->runningInConsole() ? true : false;
    }


    public function loadAndPublishViews()
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->getDotedNamespace());

        if ($this->runningInConsoleCommand())
        {
            $this->publishes([
                $this->getViewsPath() => resource_path('views/vendor/' . $this->getDashedNamespace())
            ], 'views');
        }
        return $this;
    }

    public function loadAndPublishTranslates()
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->getDotedNamespace());

        if ($this->runningInConsoleCommand()) {
            $this->publishes([
                $this->getTranslationsPath() => resource_path('lang/vendor/' . $this->getDashedNamespace())
            ], 'lang');
        }
        return $this;
    }

    public function publishPublicFolder($path = null)
    {
        if ($this->runningInConsoleCommand()) {
            if (empty($path)) {
                $needles = 'plugins.' ? 'vendor/core/' . $this->getDashedNamespace() : 'vendor/core';
                $path = Str::contains($this->getDotedNamespace(), $needles);
            }
            $this->publishes([$this->getPublicPath() => public_path($path)], 'public');
        }
    }

    public function publishAssetsFolder()
    {
        if ($this->runningInConsoleCommand()) {
            $this->publishes([
                $this->getAssetsPath() => resource_path('assets/vendor/' . $this->getDashedNamespace())
            ], 'assets');
        }
        return $this;
    }
}
