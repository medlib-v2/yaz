<?php

namespace Medlib\Yaz\Query;

use Medlib\Yaz\Factory\YazFactory;

class YazQuery extends YazFactory
{
    /**
     * YazQuery constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return YazQuery
     */
    public static function create()
    {
        return new self;
	}
}