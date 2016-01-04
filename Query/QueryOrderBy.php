<?php

/*
 * This file is part of the medlib application.
 * (c) 2015 Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * QueryOrderBy
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Query;

use Yaz\Exception\QueryException;
use Yaz\Factory\YazFactory;

class QueryOrderBy extends YazFactory
{

	private $_ORDER_ALLOW = [
      'ASC' => 'a',
      'IASC' => 'ia',
      'SASC' => 'sa',
      'DESC' => 'd',
      'IDESC' => 'id',
      'SDESC' => 'sd'
	];

	public function __construct($conn, $order, $parts) {

		$this->_conn = $conn;
		$this->_parts = $parts;

		$configuration = $parts['conf'];

		$sort_order = '';

		$_sort = explode(' ', $order);

		if(count($_sort) > 1) {

			if((count($_sort) % 2) != 0) {
				throw new QueryException('The orderBy is not properly formatted');
			}
			else {

				foreach($_sort AS $key => $value) {

					if($key % 2 != 0) {

						if(!array_key_exists($value, $this->_ORDER_ALLOW)) {
							throw new QueryException(sprintf('The orderBy is not properly formatted. The %s is not recognized', $value));
						}
						else {
							$sort_order .= ' ' . $this->_ORDER_ALLOW[$value];
						}
					}
					else {
						$sort_order .= ' ' . $configuration[$_sort[$key]];
					}
				}
			}
		}

		else {
			// Ascendant default
			$sort_order = $configuration[$sort[1]] . ' a';
		}

		$parts['order'] = trim($sort_order);
		yaz_sort($this->_conn, $sort_order);
		parent::__construct($this->_conn, $order, $this->_parts);
	}
}