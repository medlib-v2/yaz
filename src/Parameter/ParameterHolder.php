<?php

namespace Medlib\Yaz\Parameter;

use Serializable;

class ParameterHolder implements Serializable
{
    protected $parameters = [];

    /**
     * ParameterHolder constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Clears all parameters associated with this request.
     */
    public function clear()
    {
        $this->parameters = [];
    }

    /**
     * Retrieves a parameter.
     *
     * @param string $name     A parameter name
     * @param mixed  $default  A default parameter value
     * @return mixed A parameter value, if the parameter exists, otherwise null
     */
    public function & get($name, $default = null)
    {
        $value = $default;

        if (array_key_exists($name, $this->parameters)) {
            $value = & $this->parameters[$name];
        }

        return $value;
    }

    /**
     * Retrieves an array of parameter names.
     *
     * @return array An indexed array of parameter names
     */
    public function getNames()
    {
        return array_keys($this->parameters);
    }

    /**
     * Retrieves an array of parameters.
     *
     * @return array An associative array of parameters
     */
    public function & getAll()
    {
        return $this->parameters;
    }

    /**
     * Indicates whether or not a parameter exists.
     *
     * @param  string $name  A parameter name
     * @return bool true, if the parameter exists, otherwise false
     */
    public function has($name)
    {
        if (array_key_exists($name, $this->parameters)) {
            return true;
        }

        return false;
    }

    /**
    * Remove a parameter.
    *
    * @param  string $name     A parameter name
    * @param  mixed  $default  A default parameter value
    *
    * @return string A parameter value, if the parameter was removed, otherwise null
    */
    public function remove($name, $default = null)
    {
        $retval = $default;

        if (array_key_exists($name, $this->parameters)) {
            $retval = $this->parameters[$name];
            unset($this->parameters[$name]);
        }

        return $retval;
    }

    /**
    * Sets a parameter.
    * If a parameter with the name already exists the value will be overridden.
    *
    * @param string $name   A parameter name
    * @param mixed  $value  A parameter value
    */
    public function set($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
    * Sets a parameter by reference.
    * If a parameter with the name already exists the value will be overridden.
    *
    * @param string $name   A parameter name
    * @param mixed  $value  A reference to a parameter value
    */
    public function setByRef($name, & $value)
    {
        $this->parameters[$name] =& $value;
    }

    /**
    * Sets an array of parameters.
    *
    * If an existing parameter name matches any of the keys in the supplied
    * array, the associated value will be overridden.
    *
    * @param array $parameters  An associative array of parameters and their associated values
    */
    public function add($parameters)
    {
        if (null === $parameters) return;
        foreach ($parameters as $key => $value) $this->parameters[$key] = $value;
    }

    /**
    * Sets an array of parameters by reference.
    *
    * If an existing parameter name matches any of the keys in the supplied
    * array, the associated value will be overridden.
    *
    * @param array $parameters  An associative array of parameters and references to their associated values
    */
    public function addByRef(& $parameters)
    {
        foreach ($parameters as $key => &$value) $this->parameters[$key] =& $value;
    }

    /**
    * Serializes the current instance.
    *
    * @return array Objects instance
    */
    public function serialize()
    {
        return serialize($this->parameters);
    }

    /**
    * Unserializes a sfParameterHolder instance.
    *
    * @param string $serialized  A serialized sfParameterHolder instance
    */
    public function unserialize($serialized)
    {
        $this->parameters = unserialize($serialized);
    }
}