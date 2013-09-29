<?php

namespace RestlessCo\Cement\Console;

use Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Application as SymfonyApplication;
use RestlessCo\Cement\Console\Command\ServerCommand;
use RestlessCo\Cement\Console\Command\BuildCommand;

class Application extends SymfonyApplication
{
    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     *
     * @return string The command name
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getCommandName(InputInterface $input)
    {
        // return 'server';
        return parent::getCommandName($input);
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new ServerCommand();
        $defaultCommands[] = new BuildCommand();
        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        // $inputDefinition->setArguments();

        return $inputDefinition;
    }
}
