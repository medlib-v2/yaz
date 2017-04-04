<?php

namespace Medlib\Yaz\Query;

use Medlib\Yaz\Factory\YazFactory;
use Medlib\Yaz\Connexion\YazConnexion;

class QueryFrom extends YazFactory
{
    protected $_conn;
    protected $from;
    protected $_parts;

    /**
     * QueryFrom constructor.
     * @param string $from
     * @param array $parts
     */
    public function __construct($from, $parts) {

        $this->_parts = $parts;
        $this->from = $from;

        $this->manager = new YazConnexion($this->from);

        $this->_conn = $this->manager->connect();

        $indexes = $this->config('indexes');
        
        $this->_parts['options'] = $indexes['indexes'];
        parent::__construct($this->_conn, $from, $this->_parts);
    }

}
