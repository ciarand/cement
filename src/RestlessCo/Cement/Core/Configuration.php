<?php

namespace RestlessCo\Cement\Core;

class Configuration implements \ArrayAccess
{
    protected $config = [];

    public function __construct($dir)
    {
        $defaults = [
            'name' => basename($dir),
            'hooks' => [],
            'timeout' => 100,
            'buildLocation' => $dir,
        ];
        $config = json_decode(
            file_get_contents("$dir/cement.json"),
            true
        );
        $this->config = $config + $defaults;

        $this->requireConfigParameters(
            ['commands', 'name', 'buildLocation']
        );
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function requireConfigParameters(array $reqs)
    {
        $missingReqs = array_diff(
            $reqs,
            array_keys($this->config)
        );
        if (!empty($missingReqs)) {
            throw new \InvalidArgumentException(
                "Missing:" . implode(PHP_EOL, $missingReqs),
                1
            );
        }
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

    public function toArray()
    {
        return $this->config;
    }
}
