<?php

namespace Medlib\Yaz\Factory;

use Illuminate\Http\Request;
use Medlib\Yaz\Parser\SimpleQuery;
use Medlib\Yaz\Parser\AdvancedQuery;

abstract class ParseFactory
{
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return SimpleQuery
     */
    public function simple(Request $request)
    {
        return new SimpleQuery($request->all());
    }

    /**
     * @param Request $request
     * @return AdvancedQuery
     */
    public function advanced(Request $request)
    {
        return new AdvancedQuery($request->all());
    }
}