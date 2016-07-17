<?php

/*
 * This file is part of the medlib application.
 * (c) 2015 Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * QueryFrom
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Query;

use Illuminate\Support\Facades\Config;
use Yaz\Factory\YazFactory;
use Yaz\Connexion\YazConnexion;

class QueryFrom extends YazFactory
{

    private $conn;
    private $from;

    public function __construct($from, $parts) {

        $this->_parts = $parts;
        $this->from = $from;

        $this->_conn = (new YazConnexion($this->from))->connect();

        $indexes = Config::get('yaz.indexes');
        
        $this->_parts['options'] = $indexes['indexes'];

        parent::__construct($this->_conn, $from, $this->_parts);
    }

}
