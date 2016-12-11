<?php

/*
 * This file is part of the medlib application.
 * (c) 2015 Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ConnexionManager
 *
 * Provides connectivity for Z3950.
 *
 * @application    medlib
 * @author     Patrick LUZOLO <luzolo_p@medlib.fr>
 *
 */

namespace Yaz\Connexion;

use Exception;
use Illuminate\Support\Facades\Config;
use Yaz\Exception\ConnexionManagerException;

class ConnexionManager {

	private
		$_server,
		$_port,
		$_database,
		$_config,
		$_url,
		$_instance;

	/**
	 * ConnexionManager constructor.
	 * @param $from
	 * @return void
	 */
	public function __construct($from) {

		$this->_config = config('yaz.zebra');
		
		if(!isset($this->_config[$from])) {
			throw new Exception('Invalid parameter given to ConnexionManager->__construct. Expected source yaz.', 1);
		}

		$this->_server = $this->_config[$from]['database']['hostname'];
		$this->_port = $this->_config[$from]['database']['port'];

        if(is_array($this->_config[$from]['database']['name'])) { $this->_database = $this->_config[$from]['database']['name'][0]; }
		else { $this->_database = $this->_config[$from]['database']['name']; }

        $this->_url = $this->_server . ':'. $this->_port .'/'. $this->_database;

		if(isset($this->_config[$from]['options']) && is_array($this->_config[$from]['options'])) {

			$this->_instance = $this->Connection(
				$this->_url,
				$this->_config[$from]['options'],
				isset($this->_config[$from]['database']['elementset']) ? $this->_config[$from]['database']['elementset'] : null
			);

		} else {
			$this->_instance = $this->Connection(
				$this->_url,
				null,
				isset($this->_config[$from]['database']['elementset']) ? $this->_config[$from]['database']['elementset'] : null
			);
		}
	}

	/**
	 * @param $dsn
	 * @param null $options
	 * @param null $elementset
	 * @return resource $connection
	 * @throws ConnexionManagerException
	 */
	private function Connection($dsn, $options = null, $elementset = null) {

		try {
			if (isset($options) && is_array($options)) {
				$connection = yaz_connect($dsn, $options);
			} else {
				$connection = yaz_connect($dsn);
			}

			if(isset($elementset)) {
				yaz_element($connection, $elementset);
			}
		}
		catch (ConnexionManagerException $e) {
			throw new ConnexionManagerException($e->toString()); }

		return $connection;
	}

	/**
	 * @return resource yaz_connect
	 */
	public function getInstance() {

		return $this->_instance;
	}
}
