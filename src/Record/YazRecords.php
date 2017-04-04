<?php

namespace Medlib\Yaz\Record;

use Medlib\MarcXML\Parser\Parser;
use Medlib\Yaz\Exception\QueryException;
use Medlib\Yaz\Exception\QueryNotAllowException;
use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class YazRecords
{
	private
		$_conn,
		$_parts,
		$_recordMode,
		$_records,
		$_recordType;

	private static $ALLOW_RECORD_MODE = ['string', 'xml', 'raw', 'syntax', 'array'];

	const
		TYPE_STRING   = 'string',
		TYPE_XML      = 'xml',
		TYPE_RAW      = 'raw',
		TYPE_SYNTAX   = 'syntax',
		TYPE_ARRAY    = 'array';

    /**
     * YazRecords constructor.
     *
     * @param resource $conn
     * @param array $parts
     * @param string $RecordsMode
     * @param array $type
     *
     * @throws QueryNotAllowException
     */
	public function __construct($conn, $parts, $RecordsMode, $type = ['type' => 'all'])
    {
		$this->_recordMode = ($RecordsMode == null) ? 'xml': $RecordsMode;
		if(!in_array($this->_recordMode, self::$ALLOW_RECORD_MODE)) {
			throw new QueryNotAllowException(sprintf('The %s is not allowed', $RecordsMode));
		} else {
			$this->_conn = $conn;
			$this->_parts = $parts;
			$this->_recordType = $type;
			$this->setRecords();
		}
	}

	/**
	 * Get Record by Position offset
     *
	 * @param $conn
	 * @param $position
	 * @param $RecordMode
     *
	 * @return $type xml
	 */
	public static function getByPosition($conn, $position, $RecordMode)
    {
		if($RecordMode == "xml") {
			//$type = "xml";
			$type = "xml; charset=marc-8,utf-8";
		} else {
			$type = $RecordMode;
		}

		try {
			$xmlContent = yaz_record($conn, $position, $type);
		} catch(QueryException $e) {
			$xmlContent = yaz_record($conn, $position + 1, $type);
		}

		return $xmlContent;
	}

	/**
	 * Prepare the of records
	 * @return void
	 */
	private function setRecords()
    {
		if($this->_recordType['type'] != 'one') {
			$this->_records['hits'] = yaz_hits($this->_conn);

			if($this->_recordType['type'] == 'limit') {
				/* Le résultat commence à 1 */
				$start = $this->_recordType['start'];
				$end = $this->_recordType['end'];
			} else {
				$start = 1;
				$end = $this->_records['hits'];
			}

			for($i = $start; $i <= ($start + $end) - 1; $i++) {

				if($this->_records['hits'] >= $i) {
				    $xml = self::getByPosition($this->_conn, $i, $this->_recordMode);
					$this->_records['records'][] = $this->parse($xml);
				}
				else { break; }
			}
		}
		else {
			$this->_records['hits'] = 1;
			$xml = self::getByPosition($this->_conn, 1, $this->_recordMode);
			$this->_records['records'][] = $this->parse($xml);
		}

	}

    /**
     * @param string $result
     * @return \Medlib\MarcXML\Records\Record
     */
	private function parse($result)
    {
        $parserResult = new QuiteSimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?>'. $result);
        $parserResult->registerXPathNamespaces([ 'marc' => 'http://www.loc.gov/MARC21/slim' ]);
        $parsed = (new Parser())->parse($parserResult);
        return $parsed;
    }

	/**
	 * Return the result of request or array empty if not result
	 * @return array
	 */
	public function getRecords()
    {
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
	public function fails()
    {
		if($this->hasError() > 0) {
		    return true;
		}
		return false;
	}

	/**
	 * Return number of records
	 * @return int
	 */
	public function getHits()
    {
        return (int) $this->_records['hits'];
    }

	/**
	 * Return the source of query.
	 * @return string
	 */
	public function getFrom()
    {
        return $this->_parts['from'];
    }

	/**
	 * Return the condition of query
	 * @return string
	 */
	public function getWhere()
    {
        return $this->_parts['where'];
    }

	/**
	 * Return the orderBy of query
	 * @return string
	 */
	public function getOrderBy()
    {
        return isset($this->_parts['order']) ? $this->_parts['order']: '';
    }

	/**
	 * Return the query.
	 * @return string
	 */
	public function getQuery()
    {
        return $this->_parts['rpn'];
    }

	/**
	 * Return the message error of query.
	 * @return string
	 */
	public function errorMessage()
    {
        return $this->_parts['error'];
    }

	/**
	 *  Return code of error in this request.
	 * @return int
	 */
	public function hasError()
    {
        return $this->_parts['error_no'];
    }

	/**
	 * Close this resource of Yaz and return null
	 * @return null
	 */
	public function close()
    {
		if ($this->_conn != null) {
			yaz_close($this->_conn);
			$this->_conn = null;
		}
		return $this->_conn;
	}

    /**
     * @return array
     */
    public static function getAllowHydrationMode()
    {
        return self::$ALLOW_RECORD_MODE;
    }
}
