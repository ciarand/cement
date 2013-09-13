<?php
namespace RestlessCo\Cement\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use RestlessCo\Cement\Core\Configuration;
use RestlessCo\Cement\Build\Builder;

class Server extends Command
{
    protected $config, $builder;

    protected function configure()
    {
        $this
            ->setName('server')
            ->setDescription('Start the Cement CI server')
            ->addArgument(
                'repo',
                InputArgument::REQUIRED,
                'The directory to perform the CI on'
            )
            ->addOption(
               'host',
               // Can't use -h as a shortcut, help already uses it
               null,
               InputOption::VALUE_OPTIONAL,
               'The hostname or ip of the host to bind to',
               '0.0.0.0'
           )
            ->addOption(
               'basedir',
               'd',
               InputOption::VALUE_OPTIONAL,
               'Application base path for mounted instances',
               '/'
           )
            ->addOption(
               'port',
               'p',
               InputOption::VALUE_OPTIONAL,
               'The port to listen on',
               4567
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input);
        $output->writeln("Starting the server!");

        $output->writeln("Running tests:");
        $this->builder->build();
        $output->writeln("Build was " . ($this->builder->succeeded ? "successful" : "a failure"));
        $output->writeln("Output:");
        $output->writeln(str_repeat('=', 7));
        $output->writeln($this->builder->output);
    }

    protected function init(InputInterface $input)
    {
        $this->config = new Configuration($input->getArgument('repo'));
        $this->builder = new Builder($this->config['RUNNER']);
    }
}
