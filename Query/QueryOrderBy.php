<?php

/**
* Class QueryOrderBy
*
* Provides connectivity for Z3950.
*
* @application    medlib
* @author     Patrick LUZOLO <luzolo_p@medlib.fr>
*
**/

namespace Yaz\Query;

use Exception;

use Yaz\YazQuery;

class QueryOrderBy extends YazQuery
{
	private $_order_allow = array(
      'ASC' => 'a',
      'IASC' => 'ia',
      'SASC' => 'sa',
      'DESC' => 'd',
      'IDESC' => 'id',
      'SDESC' => 'sd'
      );
    
  public function __construct($connexion, $order, $parts)
  {
    $configuration = $parts['conf'];
    
    $sort_order = '';
    
    $_sort = explode(' ', $order);
    
    if(count($_sort) > 1)
    {
	    if((count($_sort) % 2) != 0)
	    {
		    throw new Exception('FEEDBACK_ORDERBY_NOT_FORMATED');
	    }
	    else 
	    {
		    foreach($_sort AS $key => $value)
		    {
			    if($key % 2 != 0)
			    {
				    if(!array_key_exists($value, $this->_order_allow))
				    {
					    throw new Exception(sprintf('FEEDBACK_ORDERBY_NOT_RECOGNIZED %s', $value));
				    }
				    
				    $sort_order.= ' ' . $this->_order_allow[$value];
				    
			    }
			    else {

				    $sort_order.= ' ' . $configuration[$_sort[$key]];
			    }
		    }
	    }
    }
    else { 
	    // Ascendant default
	    $sort_order = $configuration[$sort[1]] . ' a';	    
    }
    $parts['order'] = trim($sort_order);
    yaz_sort($connexion, $sort_order);
    
    parent::__construct($connexion, $order, $parts);
  }
}