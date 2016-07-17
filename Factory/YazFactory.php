<?php

/*
 * This file is part of the medlib application.
 * (c) 2015 Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * YazFactory
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Factory;

use Medlib\YAZ\Query\QueryClose;
use Yaz\Query\QueryFrom;
use Yaz\Query\QueryWhere;
use Yaz\Query\QueryLimit;
use Yaz\Query\QueryOrderBy;
use Yaz\Record\YazRecords;
use Yaz\Exception\QueryNotAllowException;

abstract class YazFactory {

	protected $_conn, $_parts;

	public function __construct(
		$connection = null,
		$dbname = null,
		$parts = [
			'from' => null,
			'where' => null,
			'conf' => null,
			'rpn' => null,
			'order' => null,
			'limit' => ['start' => null, 'end' => null],
			'syntax' => null,
			'error_no' => null,
			'error' => null,
			'options' => null
		])
	{
		$this->_parts = $parts;
		$this->_conn = $connection;

	}

	/**
	 * Set the source which the query is targeting.
	 *
	 * @param  string  $source
	 * @return \Yaz\Query\QueryFrom
	 */
	public function from($source) {

		$this->_parts['from'] = $source;
		return new QueryFrom($source, $this->_parts);
	}

	public function where($query) {

		$this->_parts['where'] = $query;
		$this->_parts['conf'] = $this->parseConfiguration($this->_conn, $this->_parts['options']);
		$this->_parts['rpn'] = $this->whereParse($this->_conn, $query);
		return new QueryWhere($this->_conn, $query, $this->_parts);
	}

	public function orderBy($order) {

		$this->_parts['order'] = $order;
		return new QueryOrderBy($this->_conn, $order, $this->_parts);
	}

	public function limit($start, $end) {

		/**
		 * La position du tableau commence à 1
		 */
		$this->_parts['limit'] = array('start' => $start + 1, 'end' => $end);
		return new QueryLimit($this->_conn, $this->_parts);
	}

	public function all($RecordsMode = null, $syntaxMode = 'marc21') {

		$this->_parts['syntax'] = $syntaxMode;
		$this->_parts = $this->find($this->_conn, $this->_parts);
		$RecordsType = isset($this->_parts['limit']) ?
			array('type' => 'limit',
                  'start' => $this->_parts['limit']['start'],
                  'end' => $this->_parts['limit']['end']):
            array('type' => 'all');
        return new YazRecords($this->_conn, $this->_parts, $RecordsMode, $RecordsType);
	}

	public function first($RecordsMode = null, $syntaxMode = 'marc21') {
		$this->_parts['syntax'] = $syntaxMode;
		$this->_parts = $this->find($this->_conn, $this->_parts);
		return new YazRecords($this->_conn, $this->_parts, $RecordsMode, array('type' =>'one'));

	}

	private function find($conn, $parts) {

		yaz_syntax($conn, $parts['syntax']);
		yaz_search($conn, "rpn", $this->_parts['rpn']);
		yaz_wait();

		$parts['error_no'] = yaz_errno($conn);
		$parts['error'] = yaz_error($conn);
		return $parts;
	}

	private function parseConfiguration($conn, $indexes) {
		yaz_ccl_conf($conn, $indexes);
		return $indexes;
	}

	/**
	 * @param $conn
	 * @param $where
	 * @return string
	 * @throws QueryNotAllowException
     */
	private function whereParse($conn, $where) {

		if(yaz_ccl_parse($conn, $where, $result)) {

			return trim($result["rpn"]);
		}
        else {
	        throw new QueryNotAllowException(sprintf('FEEDBACK_YAZ_PARSING_ERROR %s',$result['errorstring']));
        }
	}

	public function getBaseRequest() {
		return $this->_parts;
	}
}
