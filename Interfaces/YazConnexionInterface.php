<?php

namespace Yaz\Interfaces;


interface YazConnexionInterface {


    public static function factory();

    /**
     * Execute the shutdown yaz connexion..
     *
     * @param $from
     * @return \Yaz\Connexion\ConnexionManager
     */
    public function connect($from);

    /**
     * Execute the shutdown yaz connexion..
     *
     * @return void
     */
    public function close();
}