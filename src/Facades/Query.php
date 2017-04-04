<?php

namespace Medlib\Yaz\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Medlib\Yaz\Facades\Query
 */
class Query extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'medlib.yaz.query';
    }
}