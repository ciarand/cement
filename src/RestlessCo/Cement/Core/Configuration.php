<?php

namespace RestlessCo\Cement\Core;

class Configuration implements \ArrayAccess {
    protected $config = [];

    public function __construct($dir)
    {
        \Dotenv::load($dir, '.cement');
        $this->requireConfigParameters(['RUNNER', 'BRANCH']);
    }

    protected function requireConfigParameters($params)
    {
        \Dotenv::required($params);
    }

    // abstract public boolean offsetExists ( mixed $offset )
    public function offsetExists($offset)
    {
        return (bool) getenv($offset);
    }

    // abstract public mixed offsetGet ( mixed $offset )
    public function offsetGet($offset)
    {
        return getenv($offset);
    }

    // abstract public void offsetSet ( mixed $offset , mixed $value )
    public function offsetSet($offset, $value)
    {
        putenv("$offset=$value");
    }

    // abstract public void offsetUnset ( mixed $offset )
    public function offsetUnset($offset)
    {
        putenv($offset);
    }

}
