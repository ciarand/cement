<?php
namespace RestlessCo\Cement\Build;

use RestlessCo\Cement\Utility\SystemCommand;

class Builder {
    protected $runner = false;

    public function __construct($runner)
    {
        $this->runner = new SystemCommand($runner);
    }

    public function build()
    {
        $this->runner->execute();
        return $this;
    }

    public function __get($name)
    {
        if (property_exists($this->runner, $name)) {
            return $this->runner->$name;
        } elseif (method_exists($this->runner, $name)) {
            return $this->runner->$name();
        }
        return null;
    }
}
