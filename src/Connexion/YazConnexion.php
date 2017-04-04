<?php

namespace Medlib\Yaz\Connexion;

use Illuminate\Support\Facades\Config;
use Medlib\Yaz\Exception\YazException;

class YazConnexion extends AbstractConnexionManager
{
    /**
     * YazConnexion constructor.
     * @param string $from
     */
	public function __construct($from)
    {
        $config = $this->config('zebra');
        $parameters = $config[$from];

        parent::__construct($parameters);
	}

    public function initialize($parameters = [])
    {
        if (!$parameters) {
            return;
        }

        parent::initialize($parameters);

        if(!$dsn = $this->getParameter('dsn')) {
            $error = 'Database configuration specifies method "dsn", but is missing dsn parameter';
            throw new YazException($error);
        }

        $this->resource = ConnexionManager::connection($this, $dsn, $this->getParameter('options'), $this->getParameter('instance'));
    }

    /**
     * Execute the shutdown yaz connexion..
     *
     * @return ConnexionManager
     */
	public function connect() {
        $this->connection = $this->resource;
        return $this->connection;
	}


	/**
	* Execute the shutdown yaz connexion.
	*
	* @return void
	*/
	public function shutdown()
	{
		if ($this->connection !== null)  {

			yaz_close($this->connection);
			$this->connection = null;

			$this->connection;
        }
    }

 }
