<?php
namespace RestlessCo\Cement\Utility;

class SystemCommand {
    protected $command = false;
    protected $_output;
    protected $_exitCode;

    public function __construct($command)
    {
        $this->command =
            $command . (false === strpos($command, '2>') ? ' 2>&1' : '');
    }

    public function execute()
    {
        exec($this->command, $this->_output, $this->_exitCode);
        return $this;
    }

    public function succeeded()
    {
        return (bool) !$this->_exitCode;
    }

    public function failed()
    {
        return (bool) !$this->succeeded();
    }

    public function exitCode()
    {
        return $this->_exitCode;
    }

    public function output()
    {
        return $this->_output;
    }
}
