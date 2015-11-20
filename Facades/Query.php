<?php

namespace Yaz\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Yaz\Facades\Query
 */
class Query extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'query'; }
}