<?php

namespace RestlessCo\Cement\Model;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

class Build
{
    const STATUS_READY = 0;
    const STATUS_WORKING = 1;

    const STATUS_FINISHED = 16;
    const STATUS_SUCCEEDED = 17;
    const STATUS_FAILED = 18;

    // The build command(s).
    // This is sent in as an array and executed in order
    protected $commands;

    // The output of the build
    protected $buildOutput = "";
    
    // Any hooks, these come in the form of
    // "afterSuccess", "afterBuild", or "afterFailure"
    // For example:
    // $hooks = [
    //     'afterSuccess' => './congratulate-yourself.sh',
    //     'afterFailure' => './cry-a-lot.sh',
    //     'afterBuild' => './post-results-for-everyone',
    // ];
    //
    // Any extra or misnamed hooks are ignored silently.
    protected $hooks;

    // The location to execute the provided build commands / hooks
    protected $buildLocation;

    // A globally unique identifier for the build
    protected $guid;

    // The current status of the build
    public $status;

    // Whether to use a different OutputInterface
    public $outputInterface;

    protected $totalRunningTime = 0;

    /**
     * The Build constructor
     *
     * @param array $commands the build command(s) to run
     * @param array $config any configuration details, including hooks
     */
    public function __construct(array $commands, array $config)
    {
        $this->commands = $commands;
        $this->hooks = $config['hooks'];
        $this->buildLocation = $config['buildLocation'];
        $this->timeout = $config['timeout'];
        $this->guid = uniqid("", true);
    }

    public function statusIs($status)
    {
        $constant = get_class() . "::STATUS_" . strtoupper($status);
        return (constant($constant) === $this->status);
    }

    public function getBuildOutput()
    {
        return $this->buildOutput;
    }

    public function getGuid()
    {
        return $this->guid;
    }

    protected function changeStatusTo($status)
    {
        $constant = get_class() . "::STATUS_" . strtoupper($status);
        $this->status = constant($constant);
    }

    public function run()
    {
        $this->changeStatusTo("working");
        $max = count($this->commands);
        $num = 0;
        foreach ($this->commands as $command) {
            $num += 1;
            $this->writeLine("[$num / $max] Executing '" . $command . "'");
            $process = $this->createAndRunProcess($command);
            if (!$process->isSuccessful()) {
                $this->changeStatusTo('failed');
                break;
            }
        }

        if ($process->isSuccessful()) {
            $this->changeStatusTo('succeeded');
            $this->runHook('afterSuccess');
        } else {
            $this->runHook('afterFailure');
        }

        $this->runHook('afterBuild');

        $this->writeLine(
            sprintf(
                "The command \"%s\" exited with %d",
                $process->getCommandLine(),
                $process->getExitCode()
            ),
            $process->isSuccessful() ? "info" : "error"
        );
        
         $this->writeLine("");
         $this->writeLine(
             sprintf(
                 "Done. Your build exited with %d.",
                 $process->isSuccessful() ? 0 : 1
             )
         );
    }

    public function runHook($hook)
    {
        if (isset($this->hooks[$hook])) {
            return $this->createAndRunProcess($this->hooks[$hook]);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function createAndRunProcess($command)
    {
        $process = new Process($command, $this->buildLocation);
        $process->setTimeout($this->timeout);
        $process->run(
            function ($type, $buffer) {
                $this->writeLine($buffer, false, false);
            }
        );
        return $process;
    }

    protected function writeLine($string, $context = 'info', $newLine = true)
    {
        $this->write(
            [[
                'string' => $string,
                'context' => $context,
                'newLine' => $newLine,
            ]]
        );
    }

    protected function write(array $strings)
    {
        foreach ($strings as $line) {
            list($open, $close) = $this->getContextTag($line['context']);
            $this->outputInterface->write(
                $open
                . $line['string']
                . $close
                . ($line['newLine'] ? PHP_EOL : "")
            );
        }
    }

    protected function getContextTag($context)
    {
        if (!$context) {
            return ["", ""];
        }
        if (true) {
            return ["<{$context}>", "</{$context}>"];
        } else {
            return ["<span class=\"{$context}\">", "</span>"];
        }
    }
}
