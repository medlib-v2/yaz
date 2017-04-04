<?php

namespace Medlib\Yaz\Pagination;

use Medlib\Yaz\Query\YazQuery;
use Medlib\Yaz\Query\QueryFrom;
use Medlib\Yaz\Record\YazRecords;

class Paginator extends Pager
{
    protected
        $page            = 1,
        $maxPerPage      = 0,
        $lastPage        = 1,
        $nbResults       = 0,
        $results,
        $fetchMode;

    /**
     * @var \Medlib\Yaz\Query\QueryFrom;
     */
    protected $query;


    public function __construct($schema, $maxPerPage = 10)
    {
        parent::__construct($schema, $maxPerPage);
        $this->setQuery(YazQuery::create()->from($schema));
    }

    /**
     * @param \Medlib\Yaz\Query\QueryFrom $query
     */
    public function setQuery(QueryFrom $query)
    {
        $this->query = $query;
    }

    /**
     * @return QueryFrom
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Initialize the pager.
     *
     * @param string $fetchMode
     * Function to be called after parameters have been set.
     */
    public function init($fetchMode = YazRecords::TYPE_XML)
    {
        $this->fetchMode = $fetchMode;
        $count = $this->getQuery()->all($this->fetchMode)->getHits();
        $this->setNbResults($count);

        $this->getQuery()->limit(0, $this->getMaxPerPage());
        if ($this->getPage() == 0 || $this->getMaxPerPage() == 0) {
            $this->setLastPage(0);
        } else {
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
            $this->setLastPage(round($this->getNbResults() / $this->getMaxPerPage()));
            $this->getQuery()->limit($offset, $this->getMaxPerPage());
        }
    }

    /**
     * Returns an array of results on the given page.
     *
     * @return array
     */
    public function getResults()
    {
        $this->results = $this->getQuery()->all($this->fetchMode);
        return $this->results->getRecords();
    }

    /**
     * Returns an object at a certain offset.
     *
     * Used internally by {@link getCurrent()}.
     *
     * @return mixed
     */
    protected function retrieveObject($offset)
    {
        $cForRetrieve = clone $this->getQuery();
        $cForRetrieve->limit($offset, 1);
        return $cForRetrieve->all();
    }
}