<?php

namespace Yaz\Services;

use Yaz\ParseQuery;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return \Yaz\YazQuery
     */
    public function register()
    {
        $this->app->singleton('query', function () {
            $yaz = new ParseQuery();
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
        return ['query'];
    }
}