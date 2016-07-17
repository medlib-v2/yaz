<?php

namespace Yaz;

use Yaz\Factory\YazFactory;

class YazQuery extends YazFactory
{
    protected
        $conn,
        $cclconf = false,
        $query = '',
        $record_start = 1,
        $record_number = 1;

    public function __construct() { }

    public static function create() {
        return new self;
	}
}