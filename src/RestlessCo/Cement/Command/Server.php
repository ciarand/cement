<?php
namespace RestlessCo\Cement\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use RestlessCo\Cement\Core\Configuration;
use RestlessCo\Cement\Build\Job;

class Server extends Command
{
    protected $input;
    protected $output;
    protected $config;
    protected $job;
    protected $twig;

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

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->initializeVariables($input, $output);
        $this->startServer();
    }

    protected function initializeVariables($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->config = new Configuration($input->getArgument('repo'));
        $this->host = $this->input->getOption('host');
        $this->baseDir = $this->input->getOption('basedir');
        $this->port = $this->input->getOption('port');
        $this->job = new Job($this->config['runner']);

        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem('views'),
            array()
        );
    }

    protected function startServer()
    {
        $loop = \React\EventLoop\Factory::create();
        $socket = new \React\Socket\Server($loop);
        $http = new \React\Http\Server($socket, $loop);

        $http->on('request', $this->serveWebContent());
        echo "Server running at http://127.0.0.1:{$this->port}\n";

        $socket->listen($this->port);
        $loop->run();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function serveWebContent()
    {
        return function ($request, $response) {
            $response->writeHead(
                // Set the status code
                200,
                // and content-type header
                array('Content-Type' => 'text/html')
            );

            switch ($request->getPath()) {
                case '/build':
                    $this->job->run();
                    $this->output->writeln("<info>Job completed</info>");
                    // No break
                default:
                    $response->end($this->renderHtml());
            }

        };
    }

    protected function renderHtml()
    {
        return $this->twig->render(
            'index.twig',
            array(
                'job' => $this->job,
            )
        );
    }
}
