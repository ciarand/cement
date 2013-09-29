<?php

namespace RestlessCo\Cement\Model;

use RestlessCo\Cement\Model\Build;
use \Iterator;

class BuildQueue implements Iterator
{
    protected $builds = [];

    protected $position = 0;

    public function enqueue(Build $build)
    {
        $this->builds[$build->getGuid()] = $build;
    }

    public function dequeue()
    {
        return array_shift($this->builds);
    }

    public function getCount()
    {
        return count($this->builds);
    }

    public function peek()
    {
        return reset($this->builds);
    }

    public function rewind()
    {
        reset($this->builds);
        $this->position = key($this->builds);
    }

    public function current()
    {
        return current($this->builds);
    }

    public function key()
    {
        return key($this->builds);
    }

    public function next()
    {
        next($this->builds);
        $this->position = key($this->builds);
    }

    public function valid()
    {
        return isset($this->builds[$this->position]);
    }
}
