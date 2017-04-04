<?php

namespace Medlib\Yaz\Query;

use Medlib\Yaz\Factory\YazFactory;

class QueryWhere extends YazFactory
{
    /**
     * QueryWhere constructor.
     * @param resource $conn
     * @param string $where
     * @param array $parts
     */
    public function __construct($conn, $where, $parts)
    {
        $this->_conn = $conn;
        $this->_parts = $parts;

        parent::__construct($this->_conn, $where, $this->_parts);
    }
}
