<?php

/*
 * This file is part of the medlib application.
 * (c) 2015 Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * YazRecords.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Record;

use Yaz\Exception\QueryNotAllowException;
use Yaz\Exception\QueryException;

class YazRecords {

	private
		$_conn,
		$_parts,
		$_recordMode,
		$_records,
		$_recordType;

	private static $ALLOW_RECORD_MODE = [ 'string', 'xml', 'raw', 'syntax', 'array' ];

	const
		TYPE_STRING   = 'string',
		TYPE_XML      = 'xml',
		TYPE_RAW      = 'raw',
		TYPE_SYNTAX   = 'syntax',
		TYPE_ARRAY    = 'array';

	/**
	 * YazRecords constructor.
	 * @param $conn
	 * @param $parts
	 * @param $RecordsMode
	 * @param array $type
	 *
	 * @return \Yaz\Record\YazRecords
	 */
	public function __construct($conn, $parts, $RecordsMode, $type = ['type' => 'all']) {

		$this->_recordMode = ($RecordsMode == null) ? 'xml': $RecordsMode;
		if(!in_array($this->_recordMode, self::$ALLOW_RECORD_MODE)) {

			throw new QueryNotAllowException(sprintf('The %s is not allowed', $RecordsMode));
		}
		else {
			$this->_conn = $conn;
			$this->_parts = $parts;
			$this->_recordType = $type;
			$this->setRecords();
		}
	}

	/**
	 * Get Record by Position offset
	 * @param $conn
	 * @param $position
	 * @param $RecordMode
	 * @return xml
	 */
	public static function getByPosition($conn, $position, $RecordMode) {

		if($RecordMode == "xml") {
			$type = "xml; charset=utf-8";
		} else {
			$type = $RecordMode;
		}

		try {
			return yaz_record($conn, $position, $type);
		} catch(QueryException $e){

			return yaz_record($conn, $position + 1, $type);
		}
	}

	/**
	 * Prepare the of records
	 * @return void
	 */
	private function setRecords() {

		if($this->_recordType['type'] != 'one') {

			$this->_records['hits'] = yaz_hits($this->_conn);
			if($this->_recordType['type'] == 'limit') {
				/* Le résultat commence à 1 */
				$start = $this->_recordType['start'];
				$end = $this->_recordType['end'];
			}
			else {
				$start = 1;
				$end = $this->_records['hits'];
			}

			for($i = $start; $i <= ($start + $end) - 1; $i++) {

				if($this->_records['hits'] >= $i) {
					$this->_records['records'][] = self::getByPosition($this->_conn, $i, $this->_recordMode);
				}
				else { break; }
			}
		}
		else {
			$this->_records['hits'] = 1;
			$this->_records['records'][] = self::getByPosition($this->_conn, 1, $this->_recordMode);
		}
	}

	/**
	 * Return the result of request or array empty if not result
	 * @return array
	 */
	public function getRecords() {

		if(empty($this->_records['records'])) {
            return $this->_records['records'] = [];
        }
        else {
            return $this->_records['records'];
        }
    }

	/**
	 * Check if request has error.
	 * @return bool
	 */
	public function fails() {

		if($this->hasError() > 0) { return true; }
		return false;
	}

	/**
	 * Return number of records
	 * @return int
	 */
	public function getHits() { return (int) $this->_records['hits']; }

	/**
	 * Return the source of query.
	 * @return string
	 */
	public function getFrom() { return $this->_parts['from']; }

	/**
	 * Return the condition of query
	 * @return string
	 */
	public function getWhere() { return $this->_parts['where']; }

	/**
	 * Return the orderBy of query
	 * @return string
	 */
	public function getOrderBy() { return isset($this->_parts['order']) ? $this->_parts['order']: ''; }

	/**
	 * Return the query.
	 * @return string
	 */
	public function getQuery() { return $this->_parts['rpn']; }

	/**
	 * Return the message error of query.
	 * @return string
	 */
	public function errorMessage() { return $this->_parts['error']; }

	/**
	 *  Return code of error in this request.
	 * @return int
	 */
	public function hasError(){ return $this->_parts['error_no']; }

	/**
	 * Close this resource of Yaz and return null
	 * @return null
	 */
	public function close() {

		if ($this->_conn != null) {
			yaz_close($this->_conn);
			$this->_conn = null;
		}
		return $this->_conn;
	}
}
