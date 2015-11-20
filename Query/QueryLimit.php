<?php

namespace Yaz\Query;

use Yaz\YazQuery;

class QueryLimit extends YazQuery {

    public function __construct($connexion, $parts)
    {
        yaz_range($connexion, $parts['limit']['start'], $parts['limit']['end']);

        parent::__construct($connexion, $parts['limit'], $parts);
    }
}