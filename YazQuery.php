<?php

namespace Yaz;

use ArrayObject;
use Illuminate\Support\Facades\Config;
use Yaz\Query\QueryFrom;
use Yaz\Query\QueryWhere;
use Yaz\Query\QueryOrderBy;
use Yaz\Query\QueryLimit;
use Yaz\Interfaces\YazQueryInterface;

class YazQuery implements YazQueryInterface {


    protected $_connexion;
    protected $_cclconf = false;
    protected $_query = '';
    protected $_start_record = 1;
    protected $_yaz_record;
    protected $_find_where;

    public $_parts = [];

    /**
     * Instance of YazQueryFactory
     *
     * @param  string  $connexion
     * @param  string  $source
     * @param  array  $parts
     * @return void
     */
    public function __construct(
        $connexion = null,
        $source = null,
        array $parts = [
            'from' => null,
            'where' => null,
            'conf' => null,
            'rpn' => null,
            'order' => null,
            'limit' => ['start' => null, 'end' => null],
            'syntax' => null,
            'error_no' => null,
            'error' => null,
            'indexes' => null]
    ) {
        $this->_connexion = $connexion;

        $indexes = Config::get('yaz.indexes');

        $this->_parts = $parts;

        $this->_parts['indexes'] = $indexes['indexes'];
    }

    /**
     * Set the source which the query is targeting.
     *
     * @param  string  $source
     * @return new instance of QueryFrom
     */
    public function from($source)
    {
        $this->_parts['from'] = $source;

        return new QueryFrom($source, $this->_parts);
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string  $conditions
     * @return new instanace of QueryWhere
     *
     */
    public function where($conditions)
    {
        $this->_parts['where'] = $conditions;

        $this->_parts['conf'] = $this->setConfiguration($this->_connexion, $this->_parts['indexes']);

        $this->_parts['rpn'] = $this->setConditions($this->_connexion, $conditions);

        return new QueryWhere($this->_connexion, $conditions, $this->_parts);
    }

    /**
     * Add an "order by" in to the query.
     *
     * @param  string  $order
     * @return new instanace of QueryOrderBy
     */
    public function orderBy($order) {

        $this->_parts['order'] = $order;

        return new QueryOrderBy($this->_connexion, $order, $this->_parts);
    }

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $start
     * @param  int  $end
     * @return new instanace of QueryLimit
     */
    public function limit($start, $end) {

        /* The table beginnig by 1 */
        $this->_parts['limit'] = [ 'start' => $start + 1, 'end' => $end ];

        return new QueryLimit($this->_connexion, $this->_parts);
    }

    public function all($record_mode = null, $default_syntax = 'marc21') {

        $this->_parts['syntax'] = $default_syntax;

        $this->_parts = $this->find($this->_connexion, $this->_parts);

        $recordType = isset($this->_parts['limit']['start']) ?
            [
                'type' 	=> 'limit',
                'start' => $this->_parts['limit']['start'],
                'end'	=> $this->_parts['limit']['end']
            ] :
            [ 'type' => 'all' ];

        return new YazRecords($this->_connexion, $this->_parts, $record_mode, $recordType);

    }

    public function first($record_mode = null, $default_syntax = 'marc21') {

        $this->_parts['syntax'] = $default_syntax;

        $this->_parts = $this->find($this->_connexion, $this->_parts);

        return new YazRecords($this->_connexion, $this->_parts, $record_mode, array('type' => 'one'));
    }

    /**
     * Execute the query statement.
     *
     * @param  instance  $connexion
     * @param  array  $parts
     * @return unmarck format $parts
     */
    private function find($connexion, $parts) {

        yaz_syntax($connexion, $parts['syntax']);
        yaz_search($connexion, "rpn", $parts['rpn']);
        yaz_wait();

        $parts['error_no'] = yaz_errno($connexion);
        $parts['error'] = yaz_error($connexion);

        return $parts;
    }

    private function setConditions($connexion, $conditions) {

        if(yaz_ccl_parse($connexion, $conditions, $result))

            return trim($result["rpn"]);
        else
            throw new \Exception(sprintf('FEEDBACK_YAZ_PARSING_ERROR %s',$result['errorstring']));
    }

    private function setConfiguration($connexion, $indexes) {

        yaz_ccl_conf($connexion, $indexes);
        return $indexes;
    }

    public function getBaseRequest() {

        return $this->_parts;
    }

}