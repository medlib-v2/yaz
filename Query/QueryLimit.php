<?php

/*
 * This file is part of the medlib application.
 * (c) 2015 Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * QueryLimit
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Query;

use Yaz\Factory\YazFactory;

class QueryLimit extends YazFactory {

  public function __construct($conn, $parts) {

    $this->_conn = $conn;
    $this->_parts = $parts;

    yaz_range($this->_conn, $this->_parts['limit']['start'], $this->_parts['limit']['end']);
    parent::__construct($this->_conn, $this->_parts['limit'], $this->_parts);
  }
}
