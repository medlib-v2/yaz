<?php

namespace Medlib\Yaz\Factory;

use Medlib\Yaz\Query\QueryFrom;
use Medlib\Yaz\Query\QueryWhere;
use Medlib\Yaz\Query\QueryLimit;
use Medlib\Yaz\Record\YazRecords;
use Medlib\Yaz\Query\QueryOrderBy;
use Medlib\Yaz\Connexion\YazConnexion;
use Medlib\Yaz\Exception\QueryNotAllowException;

abstract class YazFactory
{
    protected $_conn;
    protected $_parts;
	protected $manager;

    /**
     * YazFactory constructor.
     * @param resource $connection
     * @param string $dbname
     * @param array $parts
     */
	public function __construct($connection = null, $dbname = null, $parts = [
			'from' => null,
			'where' => null,
			'conf' => null,
			'rpn' => null,
			'order' => null,
			'limit' => null,
			'syntax' => null,
			'error_no' => null,
			'error' => null,
			'options' => null
		])
	{
		$this->_conn = $connection;
        $this->_parts = $parts;
	}

	/**
	 * Set the source which the query is targeting.
	 *
	 * @param  string  $source
	 * @return \Medlib\Yaz\Query\QueryFrom
	 */
	public function from($source)
    {
		$this->_parts['from'] = $source;
		return new QueryFrom($source, $this->_parts);
	}

	public function where($query)
    {
		$this->_parts['where'] = $query;
		$this->_parts['conf'] = $this->parseConfiguration($this->_conn, $this->_parts['options']);
		$this->_parts['rpn'] = $this->whereParse($this->_conn, $query);
		return new QueryWhere($this->_conn, $query, $this->_parts);
	}

	public function orderBy($order)
    {
        $this->_parts['order'] = $order;
		return new QueryOrderBy($this->_conn, $order, $this->_parts);
	}

	/**
	 * Limit the query offset
	 * @param $start
	 * @param $end
	 * @return QueryLimit
	 */
	public function limit($start, $end)
    {
		/**
		 * La position du tableau commence à 1
		 */
		$this->_parts['limit'] = ['start' => $start + 1, 'end' => $end];
		return new QueryLimit($this->_conn, $this->_parts);
	}

	/**
	 * Return all result
	 * @param null $RecordsMode
	 * @param string $syntaxMode
	 * @return YazRecords
	 */
	public function all($RecordsMode = null, $syntaxMode = 'marc21')
    {
        $this->_parts['syntax'] = $syntaxMode;
		$this->_parts = $this->find($this->_conn, $this->_parts);
		$RecordsType = isset($this->_parts['limit']) ?
			['type' => 'limit',
                  'start' => $this->_parts['limit']['start'],
                  'end' => $this->_parts['limit']['end']]:
            ['type' => 'all'];
        return new YazRecords($this->_conn, $this->_parts, $RecordsMode, $RecordsType);
	}

	/**
	 * Return the first result
	 * @param null $RecordsMode
	 * @param string $syntaxMode
	 * @return YazRecords
	 */
	public function first($RecordsMode = null, $syntaxMode = 'marc21')
    {
		$this->_parts['syntax'] = $syntaxMode;
		$this->_parts = $this->find($this->_conn, $this->_parts);
		return new YazRecords($this->_conn, $this->_parts, $RecordsMode, ['type' =>'one']);

	}

	/**
	 * Send request to yaz resource
	 * @param $conn
	 * @param $parts
	 * @return mixed
	 */
	private function find($conn, $parts)
    {

		yaz_syntax($conn, $parts['syntax']);
		yaz_search($conn, "rpn", $this->_parts['rpn']);
		yaz_wait();

		$parts['error_no'] = yaz_errno($conn);
		$parts['error'] = yaz_error($conn);
		return $parts;
	}

	/**
	 * Parsing the indexes setting
	 * @param $conn
	 * @param $indexes
	 * @return mixed
	 */
	private function parseConfiguration($conn, $indexes)
    {
		yaz_ccl_conf($conn, $indexes);
		return $indexes;
	}

	/**
	 * @param $conn
	 * @param $where
	 * @return string
	 * @throws QueryNotAllowException
     */
	private function whereParse($conn, $where)
    {

		if(yaz_ccl_parse($conn, $where, $result)) {

			return trim($result["rpn"]);
		}
        else {
	        throw new QueryNotAllowException(sprintf('Parsing error: %s', $result['errorstring']));
        }
	}

	/**
	 * Return the user query
	 * @return array
	 */
	public function getBaseRequest()
    {
		return $this->_parts;
	}

    /**
     * @return void
     */
	public function close()
    {
        $this->manager->shutdown();
    }

    /**
     * Helper to get the config values.
     *
     * @param string $key
     * @param string $point
     * @param string $default
     * @return string
     */
    protected function config($key, $point = null, $default = null)
    {
        if ($point === null) {
            $path = "yaz.$key";
        } else {
            $path = "yaz.$point.$key";
        }

        return config($path, $default);
    }
}
