<?php

namespace Medlib\Yaz\Parser;


use Medlib\Yaz\Factory\ParseFactory;

class ParseQuery extends ParseFactory
{
    public function __construct() {
        parent::__construct();
    }

    public static function create() {
        return new self;
    }
}