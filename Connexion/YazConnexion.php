<?php

/*
 * This file is part of the medlib application.
 * (c) 2015 Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * YazConnexion
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Connexion;

use Yaz\Exception\YazNotLoadedException;

class YazConnexion {

	/**
	 * @var \Yaz\Connexion\ConnexionManager $connection
	 */
	private $connection;

	/**
	 * @var self $instance
	 */
	private static $instance;

	public function __construct($from) {
        self::init();
		static::$instance = new ConnexionManager($from);
	}

	/**
	 * Execute the shutdown yaz connexion..
	 *x
	 * @return yaz_resource
	 */
	public function connect() {
        $this->connection = static::$instance->getInstance();
        return $this->connection;
	}

	/**
	public function options() {
		return $this->instance->getIndexes();
	}
	 */

	/**
	* Execute the shutdown yaz connexion.
	*
	* @return null
	*/
	public function close()
	{
		if ($this->connection !== null)  {

			yaz_close($this->connection);
			$this->connection = null;

			return $this->connection;
        }
    }

    private static function init()
    {
        if(!function_exists('yaz_connect'))  {
			
            throw new YazNotLoadedException("Yaz module is not installed");
        }
    }

 }
