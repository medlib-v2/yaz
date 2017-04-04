<?php

namespace Medlib\Yaz\Parser;

use DateTime;
use DateTimeZone;
use Medlib\Yaz\Factory\ParseFactory;

class SimpleQuery extends ParseFactory
{
    private $_query = [];
    private $_StringQuery;

    public function __construct(array $query)
    {
        $this->_query = $query;
    }

    /**
     *
     * Cette fonction analyser la requête utilisateur
     * @return string query
     */
    public function get()
    {

        if(!empty($this->_query) and is_array($this->_query)) {

            // Titres
            if(isset($this->_query['title'])) {

                $this->_StringQuery = $this->_query['title'] .'='. '"'.$this->_query['query'].'"';
            }

            //Auteurs
            if(isset($this->_query['author']) and isset($this->_query['title'])) {

                $this->_StringQuery .= ' or ' . $this->_query['author'] .'='. '"'.$this->_query['query'].'"';

            } elseif(isset($this->_query['author'])) {

                $this->_StringQuery = $this->_query['author'] .'='. '"'.$this->_query['query'].'"';
            }

            // Editeurs
            if(isset($this->_query['publisher']) and (
                    isset($this->_query['title']) or
                    isset($this->_query['author']))) {

                $this->_StringQuery .= ' or ' . $this->_query['publisher'] .'='. '"'.$this->_query['query'].'"';

            } elseif(isset($this->_query['publisher'])) {

                $this->_StringQuery = $this->_query['publisher'] .'='. '"'.$this->_query['query'].'"';
            }

            // Parse Titres uniformes
            if(isset($this->_query['uniforme']) and (
                    isset($this->_query['title']) or
                    isset($this->_query['author']) or
                    isset($this->_query['publisher']))) {

                $this->_StringQuery .= ' or '. $this->_query['uniforme'] .'='. '"'.$this->_query['query'].'"';

            } elseif(isset($this->_query['uniforme'])) {

                $this->_StringQuery = $this->_query['uniforme'] .'='. '"'.$this->_query['query'].'"';
            }

            // Mots-clés
            if( isset($this->_query['keywords']) and
                (
                    isset($this->_query['uniforme']) or
                    isset($this->_query['title']) or
                    isset($this->_query['author']) or
                    isset($this->_query['publisher']))) {

                $this->_StringQuery .= ' or '. $this->_query['keywords'] .'='. '"'.$this->_query['query'].'"';

            } elseif(isset($this->_query['keywords'])) {

                $this->_StringQuery = $this->_query['keywords'] .'='. '"'.$this->_query['query'].'"';
            }

            // Date de publication
            if( isset($this->_query['dofpublisher']) and (
                    isset($this->_query['keywords']) or
                    isset($this->_query['uniforme']) or
                    isset($this->_query['title']) or
                    isset($this->_query['author']) or
                    isset($this->_query['publisher']))) {

                if(self::is_date($this->_query['query'])) {

                    $this->_StringQuery .= ' or '. $this->_query['dofpublisher'] .'='. '"'.$this->_query['query'].'"';
                }

            }
            elseif(isset($this->_query['dofpublisher'])) {

                if(self::is_date($this->_query['query'])) {

                    $this->_StringQuery = $this->_query['dofpublisher'] .'='. '"'.$this->_query['query'].'"';
                }
            }

            /**
             *
             * @return string query
             */
            return $this->_StringQuery;
        }
    }

    private static function is_date($str) {

        $str = str_replace('/', '-', $str);
        $stamp = strtotime($str);

        if (is_numeric($stamp)) {

            $month = date( 'm', $stamp );
            $day   = date( 'd', $stamp );
            $year  = date( 'Y', $stamp );

            return checkdate($month, $day, $year);

        }
        return false;
    }

    function isValidDateTimeString($str_dt, $str_dateformat, $str_timezone) {
        $date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));
        return $date && $date->format($str_dateformat) == $str_dt;
    }

}