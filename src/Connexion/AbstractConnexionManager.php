<?php

namespace Medlib\Yaz\Connexion;

use Illuminate\Support\Facades\Config;
use Medlib\Yaz\Parameter\ParameterHolder;
use Medlib\Yaz\Exception\YazNotLoadedException;

abstract class AbstractConnexionManager
{
    protected
        $parameterHolder = null,
        /**
         * @var \Medlib\Yaz\Connexion\ConnexionManager $connection
         */
        $connection      = null,
        $resource        = null;

    /**
     * AbstractConnexionManager constructor.
     * @see initialize()
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->init();
        $this->initialize($parameters);
    }

    public function initialize($parameters = [])
    {
        $this->parameterHolder = new ParameterHolder;
        $this->parameterHolder->add($parameters);
    }

    private function init()
    {
        if(!function_exists('yaz_connect'))  {

            throw new YazNotLoadedException("Yaz module is not installed");
        }
    }

    /**
    * Connects to the yaz database.
    *
    * @throws \Medlib\Yaz\Exception\ConnexionManagerException If a connection could not be created
    */
    abstract function connect();

    /**
    * Retrieves the database connection associated with this AbstractConnexionManager implementation.
    *
    * When this is executed on a Database implementation that isn't an
    * abstraction layer, a copy of the resource will be returned.
    *
    * @return mixed A database connection
    *
    * @throws \Medlib\Yaz\Exception\YazException If a connection could not be retrieved
    */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Retrieves a raw database resource associated with this AbstractConnexionManager implementation.
     *
     * @return mixed A database resource
     *
     * @throws \Medlib\Yaz\Exception\YazException If a resource could not be retrieved
     */
    public function getResource()
    {
        if (null === $this->resource)
        {
            $this->connect();
        }
        return $this->resource;
    }

    /**
     * Gets the parameter holder for this object.
    *
    * @return ParameterHolder A ParameterHolder instance
    */
    public function getParameterHolder()
    {
        return $this->parameterHolder;
    }

    /**
     * Gets the parameter associated with the given key.
     *
     * This is a shortcut for:
     *
     * <code>
     *  $this->getParameterHolder()->get()
     * </code>
     *
     * @param string $name    The key name
     * @param string $default The default value
     *
     * @return array|string The value associated with the key
     *
     * @see ParameterHolder
     */
    public function getParameter($name, $default = null)
    {
        return $this->parameterHolder->get($name, $default);
    }

    /**
     * Returns true if the given key exists in the parameter holder.
     * This is a shortcut for:
     * <code>
     *  $this->getParameterHolder()->has()
     * </code>
     *  @see ParameterHolder
     *
     * @param string $name The key name
     * @return boolean true if the given key exists, false otherwise
     */
    public function hasParameter($name)
    {
        return $this->parameterHolder->has($name);
    }

    /**
     * Sets the value for the given key.
     *
     * This is a shortcut for:
     *
     * <code>
     *  $this->getParameterHolder()->set()
     * </code>
     * @see ParameterHolder
     *
     * @param string $name  The key name
     * @param string $value The value
     */
    public function setParameter($name, $value)
    {
        $this->parameterHolder->set($name, $value);
    }

    /**
     * Helper to get the config values.
     *
     * @param string $key
     * @param string $point
     * @param string $default
     * @return string
     */
    protected function config($key, $point = null, $default = null)
    {
        if ($point === null) {
            $path = "yaz.$key";
        } else {
            $path = "yaz.$point.$key";
        }

        return config($path, $default);
    }

    /**
     * Executes the shutdown procedure.
     *
     * @return void
     *
     * @throws \Medlib\Yaz\Exception\ConnexionManagerException If an error occurs while shutting down this database
     */
    abstract function shutdown();
}