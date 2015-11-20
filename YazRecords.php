<?php

namespace Yaz;

use Yaz\Config\YazConfig;
use Yaz\Exceptions\QueryNotAllowException;

class YazRecords
{
    private
        $_conn,
        $_parts,
        $_recordMode,
        $_records,
        $_recordType;

    public function __construct($conn, $parts, $recordMode, array $type = ['type' => 'all']) {

        $this->_recordMode = ($recordMode == null) ? 'xml': $recordMode;

        if(!in_array($this->_recordMode, YazConfig::getAllowRecordMode()))
        {
            throw new QueryNotAllowException(sprintf('The %s is not allowed', $recordMode));
        }
        else
        {
            $this->_conn = $conn;
            $this->_parts = $parts;
            $this->_recordType = $type;
            $this->setRecords();
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
                $end = $this->_records['hits'];;
            }

            for($i = $start; $i <= ($start + $end) - 1; $i++) {

                if($this->_records['hits'] >= $i) {

                    $this->_records['records'][] = YazConfig::getByPosition($this->_conn, $i, $this->_recordMode);
                }
                else { break; }
            }
        }
        else {

            $this->_records['hits'] = 1;
            $this->_records['records'][] = YazConfig::getByPosition($this->_conn, 1, $this->_recordMode);
        }
    }

    /**
     * Return the result of request if not empty
     * @return array
     */
    public function getRecords() {

        if(empty($this->_records['records']))
        {
            return $this->_records['records'] = array();
        }
        else {
            return $this->_records['records'];
        }
    }

    /**
     * Return number of records
     * @return int
     */
    public function getHits() { return (int) $this->_records['hits']; }

    /**
     * Return the source of query.
     * @return mixed
     */
    public function getFrom() { return $this->_parts['from']; }

    /**
     * Return the condition of query
     * @return mixed
     */
    public function getWhere() { return $this->_parts['where']; }

    /**
     * Return the orderBy of query
     * @return string
     */
    public function getOrderBy() { return isset($this->_parts['order']) ? $this->_parts['order']: ''; }

    /**
     * Return the query.
     * @return mixed
     */
    public function getQuery() { return $this->_parts['rpn']; }

    /**
     * Return the message error of query.
     * @return mixed
     */
    public function errorMessage() { return $this->_parts['error']; }

    /**
     *  Return code of error in this request.
     * @return mixed
     */
    private function hasError(){ return $this->_parts['error_no']; }

    /**
     * Check if request has error.
     * @return bool
     */
    public function fails() {

        if($this->hasError() > 0)
        {
            return true;
        }
        return false;
    }
}