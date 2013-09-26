<?php
namespace RestlessCo\Cement\Build;

class Job
{
    public $jobs = [];
    public $command = false;

    public function __construct($command)
    {
        $this->command = $command . (
                // If we're not already piping the stderr, pipe it
                false === strpos($command, '2>')
                    ? ' 2>&1'
                    : ''
            );
    }

    public function run()
    {
        $time = microtime(true);
        exec(
            $this->command,
            $this->jobs[$time]['output'],
            $this->jobs[$time]['exitCode']
        );
    }

    public function getOutput()
    {
        return implode(
            PHP_EOL,
            end($this->jobs)['output']
        );
    }

    public function getExitCode()
    {
        return end($this->jobs)['exitCode'];
    }
}
