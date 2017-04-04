<?php

namespace Medlib\Yaz\Providers;

use Medlib\Yaz\Query\YazQuery;
use Medlib\Yaz\Parser\ParseQuery;
use Illuminate\Support\ServiceProvider;

class YazServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Config
         */
        $this->publishes([
            __DIR__.'/../../config' => base_path('config')
        ]);

        $this->bootBindings();
    }

    protected function bootBindings()
    {
        $this->app['Medlib\Yaz\Query\YazQuery'] = function ($app) {
            return $app['medlib.yaz.yaz'];
        };

        $this->app['Medlib\Yaz\Parser\ParseQuery'] = function ($app) {
            return $app['medlib.yaz.query'];
        };
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerYazProvider();
        $this->registerQueryProvider();
    }

    /**
     * Register the bindings for the Yaz Query provider.
     *
     * @return void
     */
    protected function registerYazProvider()
    {
        $this->app->singleton('medlib.yaz.yaz', function () {
            $yaz = new YazQuery;
            return $yaz;
        });
    }

    /**
     * Register the bindings for the Pa provider.
     *
     * @return void
     */
    protected function registerQueryProvider()
    {
        $this->app->singleton('medlib.yaz.query', function () {
            $parser = new ParseQuery;
            return $parser;
        });
    }
}