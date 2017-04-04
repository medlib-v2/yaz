<?php

namespace Medlib\Yaz\Connexion;

use Medlib\Yaz\Exception\ConnexionManagerException;

class ConnexionManager {

    /**
     * @var YazConnexion
     */
	private $manager;

    /**
     * @param YazConnexion $manager
     * @param string $dsn
     * @param array $options
     * @param string $name
     * @return resource
     */
    public static function connection(YazConnexion $manager, $dsn, $options, $name)
    {
        return self::getInstance()->openConnection($manager, $dsn, $options, $name);
    }

    /**
     * @param YazConnexion $manager
     * @param string $dsn
     * @param array $options
     * @param string $name
     * @return resource yaz_connect
     * @throws ConnexionManagerException
     */
	private function openConnection(YazConnexion $manager, $dsn, $options, $name)
    {
        $this->manager = $manager;
        $params = $this->parseDSN($dsn);

        $options = array_merge($options, ['scheme', 'hostname', 'database', 'username', 'password', 'port']);

        $this->setYazParameter($params, $options);

        if($params = $manager->getParameter('options')) {
            $options = array_merge($options, ['protocol', 'group', 'cookie', 'proxy', 'persistent', 'piggyback', 'charset', 'preferredMessageSize', 'maximumRecordSize']);
            $this->setYazParameter($params, $options);
        }

		try {
            $hostname = $this->manager->getParameter('hostname');
            $database = $this->manager->getParameter('database');
            $port = $this->manager->getParameter('port', 210);

            $zurl = "$hostname:$port/$database";

            if(!$protocol = $this->manager->getParameter('protocol')) {
                $connection = yaz_connect($zurl);
            } else {
                $conf = [
                    'protocol'   => $this->manager->getParameter('protocol', 2),
                    'user'       => $this->manager->getParameter('username'),
                    'password'   => $this->manager->getParameter('password'),
                    'group'      => $this->manager->getParameter('group'),
                    'cookie'     => $this->manager->getParameter('cookie'),
                    'proxy'      => $this->manager->getParameter('proxy'),
                    'persistent' => $this->manager->getParameter('persistent', true),
                    'piggyback'  => $this->manager->getParameter('piggyback', true),
                    'charset'    => $this->manager->getParameter('charset'),
                    'preferredMessageSize' => $this->manager->getParameter('preferredMessageSize'),
                    'maximumRecordSize' => $this->manager->getParameter('maximumRecordSize'),
                ];
                $options = $this->parseOptionsWithProtocol($protocol, $conf);
                $connection = yaz_connect($zurl, $options);
            }

            if ($elementset = $this->manager->getParameter('elementset')) {

                yaz_element($connection, $elementset);
            }
		}
		catch (ConnexionManagerException $e) {
			throw new ConnexionManagerException($e->toString()); }

		return $connection;
	}

    private function setYazParameter($params, $options)
    {
        foreach ($options as $option) {
            if (!$this->manager->getParameter($option) && isset($params[$option])) {
                $this->manager->setParameter($option, $params[$option]);
            }
        }
    }

    /**
     * @param $protocol
     * @param $options
     * @return array|bool|string
     */
    private function parseOptionsWithProtocol($protocol, $options)
    {
        $parsed = ($protocol == 2) ? [] : '';

        foreach($options as $key => $value) {
            if($key == 'persistent' || $key == 'piggyback') {
                $value = (!$value) ? '0' : '1';
            }

            if($value != '') {
                if($key != 'protocol') {
                    if(!$protocol == 2) {
                        $parsed .= "$key=$value,";
                    }
                    else {
                        $parsed[$key] = $value;
                    }
                }
            }
        }

        if(!$protocol == 2) {
            $parsed = substr($parsed, 0, -1);
        }

        return $parsed;
    }

    private function parseDSN($dsn)
    {
        if (is_array($dsn)) {
            return $dsn;
        }
        $parsed = array(
            'scheme'  => null,
            'username' => null,
            'password' => null,
            'hostname' => null,
            'port'     => null,
            'database' => null
        );

        $info = parse_url($dsn);

        // if there's only one element in result, then it must be the scheme
        if (count($info) === 1) {
            $parsed['scheme'] = array_pop($info);
            return $parsed;
        }
        // some values can be copied directly
        $parsed['scheme'] = @$info['scheme'];
        $parsed['username'] = @$info['user'];
        $parsed['password'] = @$info['pass'];
        $parsed['port'] = @$info['port'];
        $host = @$info['host'];
        $parsed['hostname'] = $host;
        if (isset($info['path'])) {
            // remove first char, which is '/'
            $parsed['database'] = substr($info['path'], 1);
        }
        return $parsed;
    }

    /**
     * @return ConnexionManager
     */
	public static function getInstance() {

        static $instance;
        if ( ! isset($instance)) {
            $instance = new self();
        }
        return $instance;
	}
}
