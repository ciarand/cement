<?php

namespace RestlessCo\Cement\Core;

class Configuration implements \ArrayAccess
{
    protected $config = [];

    public function __construct($dir)
    {
        $this->config = json_decode(
            file_get_contents($dir . '/cement.json'),
            true
        );
        $this->requireConfigParameters(['runner', 'branch']);
    }

    protected function requireConfigParameters($params)
    {
        return empty(array_diff(array_flip($params), $this->config));
    }

    // abstract public boolean offsetExists ( mixed $offset )
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    // abstract public mixed offsetGet ( mixed $offset )
    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    // abstract public void offsetSet ( mixed $offset , mixed $value )
    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    // abstract public void offsetUnset ( mixed $offset )
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }
}
