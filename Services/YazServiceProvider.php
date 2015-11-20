<?php

namespace Yaz\Services;

use Yaz\YazQuery;
use Illuminate\Support\ServiceProvider;

class YazServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return \Yaz\YazQuery
     */
    public function register()
    {
        $this->app->singleton('yaz', function () {
            $yaz = new YazQuery();
            return $yaz;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['yaz'];
    }
}