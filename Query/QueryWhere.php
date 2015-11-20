<?php

/**
 * QueryWhere
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Query;

use Yaz\YazQuery;

class QueryWhere extends YazQuery
{
  public function __construct($connexion, $conditions, $parts)
  {
    parent::__construct($connexion, $conditions, $parts);
  }
}