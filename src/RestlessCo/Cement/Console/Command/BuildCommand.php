<?php
namespace RestlessCo\Cement\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use RestlessCo\Cement\Core\Configuration;
use RestlessCo\Cement\Model\BuildQueue;
use RestlessCo\Cement\Model\Build;

class BuildCommand extends Command
{
    protected $input;
    protected $output;
    protected $config;

    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Build the project')
            ->addArgument(
                'repo',
                InputArgument::REQUIRED,
                'The directory to perform the CI on'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initializeVariables($input, $output);
        $build = new Build($this->config['commands'], $this->config->toArray());
        $build->outputInterface = $this->output;
        $build->run();
    }

    protected function initializeVariables($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->config = new Configuration($input->getArgument('repo'));
        $this->buildQueue = new BuildQueue();
    }
}
