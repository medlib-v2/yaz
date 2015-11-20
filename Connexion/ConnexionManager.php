<?php

namespace Yaz\Connexion;

use Exception;
use Illuminate\Support\Facades\Config;
use Yaz\Exceptions\ConnexionManagerException;

class ConnexionManager {

    private
        $_server,
        $_port,
        $_database,
        $_url,
        $_instance;

    public function __construct($from) {

        $config = Config::get('yaz.zebra');

        if(isset($config[$from])) {

            $this->_server = $config[$from]['database']['hostname'];

            $this->_port = $config[$from]['database']['port'];

            if(isset($config[$from]['database']['name'][0]))
            {
                $this->_database = $config[$from]['database']['name'][0];
            }
            else {
                $this->_database = $config[$from]['database']['name'];
            }
            $this->_url = $this->_server . ':'. $this->_port .'/'. $this->_database;

            if(isset($config[$from]['options'])) {

                $this->_instance = $this->Connection(
                    $this->_url, $config[$from]['options'],
                    isset($config[$from]['database']['elementset']) ? $config[$from]['database']['elementset'] : null
                );
            }
            else {

                $this->_instance = $this->Connection(
                    $this->_url, null,
                    isset($config[$from]['database']['elementset']) ? $config[$from]['database']['elementset'] : null
                );
            }
        } else {
            throw new Exception('Invalid parameter given to ConnexionManager->__construct. Expected source yaz.', 1);
        }
    }

    private function Connection($dsn, $options = null, $elementset = null)
    {
        try{
            if (isset($options) && is_array($options))
            {
                $connexion = yaz_connect($dsn, $options);
            }
            else {
                $connexion = yaz_connect($dsn);
            }

            $this->elementset($connexion, $elementset);
        }
        catch (ConnexionManagerException $e) {
            throw new ConnexionManagerException($e->toString()); }

        return $connexion;
    }

    public function getInstance()
    {
        return $this->_instance;
    }

    private function elementset($connexion, $elementset)
    {
        if(isset($elementset))  yaz_element($connexion, $elementset);
    }
}