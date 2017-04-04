<?php

namespace Medlib\Yaz\Parser;

use Medlib\Yaz\Factory\ParseFactory;

class AdvancedQuery extends ParseFactory
{

    private $_query = [];
    private $_AdvancedQuery;

    /**
     * AdvancedQuery constructor.
     *
     * @param array $query
     */
    public function __construct(array $query)
    {

        $this->_query = $query;
    }

    /**
     * Cette fonction analyser la requête utilisateur
     * @return string query
     */
    public function get()
    {

        if(!empty($this->_query) and is_array($this->_query)) {

            $words = $this->_query['words'];
            $from = isset($this->_query['qdb']) ? $this->_query['qdb']: null;
            $place = isset($this->_query['place']) ? $this->_query['place']: null;
            $language = isset($this->_query['language']) ? $this->_query['language'] : null;
            $datePub = isset($this->_query['datePub']) ? $this->_query['datePub'] : null;
            $typeDoc = isset($this->_query['typeDoc']) ? $this->_query['typeDoc'] : null;

            foreach ($words as $search ) {
                if ($search['condition'] == -1) {
                    $this->_AdvancedQuery = $search['title'] .'='. '"'.$search['query'].'"';
                }
                else {
                    $this->_AdvancedQuery .= ' '.$search['condition']. ' ' .$search['title']. '='. '"'.$search['query'].'"';
                }
            }

            if(isset($place)) {
                if (isset($from) and $from == 'SUDOC') $this->_AdvancedQuery .= ' and cna="'. $place.'"';
                elseif (isset($from)) $this->_AdvancedQuery .= ' and pub="'. $place.'"';
            }

            if(isset($language)) {

                if (isset($from) and $from == 'SUDOC') {

                    if($language !== 'ALL') $this->_AdvancedQuery .= ' and ln="'. $language.'"';

                } else {
                    $this->_AdvancedQuery .= ' and ln="'. $language.'"';
                }
            }

            if(isset($datePub) and isset($datePub['yearOption']) and $datePub['yearOption'] == 'predefined') {

                $this->_AdvancedQuery .= ' and yr="'. $datePub['year'].'"';

            } elseif (isset($datePub) and isset($datePub['yearOption']) and $datePub['yearOption'] == 'range') {
                $date = $datePub['fromYear'].'-'.$datePub['toYear'];
                $this->_AdvancedQuery .= ' and yr="'. $date.'"';

            }

            if(isset($typeDoc)) {
                $i = 1;

                foreach ($typeDoc as $value) {

                    if($i == 1) $this->_AdvancedQuery .= ' and idc="'. $value.'"';

                    else $this->_AdvancedQuery .= ' or idc="'. $value.'"';

                    $i++;
                }
            }
        }

        /**
         * @return string query
         */
        return $this->_AdvancedQuery;
    }

}