<?php

namespace Yaz\Interfaces;


interface YazQueryInterface {

    /**
     * Set the source which the query is targeting.
     *
     * @param  string  $source
     * @return new instance of QueryFrom
     */
    public function from($source);

    /**
     * Add a basic where clause to the query.
     *
     * @param  string  $conditions
     * @return new instanace of QueryWhere
     *
     */
    public function where($conditions);

    /**
     * Add an "order by" in to the query.
     *
     * @param  string  $order
     * @return new instanace of QueryOrderBy
     */
    public function orderBy($order);

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $start
     * @param  int  $end
     * @return new instanace of QueryLimit
     */
    public function limit($start, $end);
}