<?php

namespace Yaz\Connexion;

use Yaz\Exceptions\YazNotLoadedException;
use Yaz\Interfaces\YazConnexionInterface;

class YazConnexion  implements YazConnexionInterface {

    /**
     * @var \Yaz\Connexion\ConnexionManager $connection
     */
    private $connection;

    /**
     * @var self $instance
     */
    private static $instance;


    public static function factory()
    {
        self::init();

        if(!isset(static::$instance)) static::$instance = new self;

        return static::$instance;
    }

    /**
     * Execute the shutdown yaz connexion..
     *
     * @param $from
     * @return \Yaz\Connexion\ConnexionManager
     */
    public function connect($from)
    {
        if(!isset($this->connection)) $this->connection = (new ConnexionManager($from))->getInstance();

        return $this->connection;
    }

    /**
     * Execute the shutdown yaz connexion..
     *
     * @return void
     */
    public function close()
    {
        if ($this->connection !== null)
        {
            yaz_close($this->connection);
            $this->connection = null;
        }
    }

    private static function init()
    {
        if(!function_exists('yaz_connect'))
        {
            throw new YazNotLoadedException('FEEDBACK_YAZ_WAS_NOT_LOADED');
        }
    }
}