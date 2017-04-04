<?php

namespace Medlib\Yaz\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Medlib\Yaz\Facades\Yaz
 */
class Yaz extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'medlib.yaz.yaz';
    }
}