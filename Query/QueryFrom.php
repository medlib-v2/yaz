<?php

/**
* Class QueryFrom
*
* Provides connectivity for Z3950.
*
* @application    medlib
* @author     Patrick LUZOLO <luzolo_p@medlib.fr>
*
**/

namespace Yaz\Query;

use Yaz\YazQuery;
use Yaz\Connexion\YazConnexion;

class QueryFrom extends YazQuery {

	/**
	 * QueryFrom constructor.
	 * @param null|string $connexion
	 * @param null|string $from
	 * @param array $parts
     */
	public function __construct($from, $parts)
	{
		$this->_parts = $parts;

		$this->_connexion = YazConnexion::factory()->connect($from);

		parent::__construct($this->_connexion, $from, $this->_parts);
	}

}
