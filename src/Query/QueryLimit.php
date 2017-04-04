<?php

namespace Medlib\Yaz\Query;

use Medlib\Yaz\Factory\YazFactory;

class QueryLimit extends YazFactory
{
    /**
     * QueryLimit constructor.
     * @param resource $conn
     * @param array $parts
     */
    public function __construct($conn, $parts)
    {
        $this->_conn = $conn;
        $this->_parts = $parts;

        yaz_range($this->_conn, $this->_parts['limit']['start'], $this->_parts['limit']['end']);
        parent::__construct($this->_conn, $this->_parts['limit'], $this->_parts);
    }
}
