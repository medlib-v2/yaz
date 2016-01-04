<?php

namespace Yaz;

use Illuminate\Http\Request;
use Yaz\ParseQuery\SimpleQuery;
use Yaz\ParseQuery\AdvancedQuery;

class ParseQuery {

    public function __construct() {

    }

    public function simple(Request $request) {

        return new SimpleQuery($request->all());
    }

    public function advanced(Request $request) {

        return new AdvancedQuery($request->all());
    }

}