<?php

namespace Modules\News\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'News';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'news';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerControllers();
        $this->registerComponents();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->publishes([
            module_path($this->moduleName, 'Database/Migrations') => database_path('migrations/tenant'),
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
    public function registerControllers()
    {
        $viewPath = base_path('app/Http/Controllers');

        $sourcePath = module_path($this->moduleName, 'assets/Http/Controllers');
        // echo $sourcePath;
        $publisers = [];
        $publisers[$sourcePath . '/api/extension/dashboard'] = $viewPath . '/api/extension/dashboard/' . $this->moduleNameLower;
        $publisers[$sourcePath . '/api/extension/module'] = $viewPath . '/api/extension/module';
        // print_r($publisers);
        $this->publishes($publisers, ['Controllers', $this->moduleNameLower . '-module-Controllers']);
    }
    /**
     * Register views.
     *
     * @return void
     */
    public function registerComponents()
    {
        $viewPath = base_path('app/View/Components');

        $sourcePath = module_path($this->moduleName, 'assets/View/Components');
        // echo $sourcePath;
        $publisers = [];
        $publisers[$sourcePath . '/dashboard'] = $viewPath . '/dashboard/' . $this->moduleNameLower;
        $publisers[$sourcePath . '/module'] = $viewPath . '/module';
        // print_r($publisers);
        $this->publishes($publisers, ['View', $this->moduleNameLower . '-module-View']);
        
        \Blade::component('news-related', \App\View\Components\module\NewsRelated::class);
        // echo 111;
    }
    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views');

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $publisers = [];
        $publisers[$sourcePath . '/components/dashboard'] = $viewPath . '/components/dashboard/' . $this->moduleNameLower;
        $publisers[$sourcePath . '/components/module'] = $viewPath . '/components/module';
        $publisers[$sourcePath . '/web'] = $viewPath . '/web/' . $this->moduleNameLower;

        $this->publishes($publisers, ['views', $this->moduleNameLower . '-module-views']);
        
        // $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
