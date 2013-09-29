<?php
namespace RestlessCo\Cement\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Dflydev\ApacheMimeTypes\PhpRepository;

use RestlessCo\Cement\Core\Configuration;
use RestlessCo\Cement\Model\BuildQueue;
use RestlessCo\Cement\Model\Build;

class ServerCommand extends Command
{
    protected $input;
    protected $output;
    protected $config;
    protected $twig;
    protected $mimeTypeRepo;

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

        // Set options
        $this->host = $this->input->getOption('host');
        $this->baseDir = $this->input->getOption('basedir');
        $this->port = $this->input->getOption('port');

        // Create the BuildQueue instance
        $this->buildQueue = new BuildQueue();

        // Create the MIME types repo
        $this->mimeTypeRepo = new PhpRepository;

        // Create the Twig environment
        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem('views'),
            array(
                'auto_reload' => true,
                'debug' => true,
                'cache' => false,
            )
        );
    }

    protected function startServer()
    {
        $loop = \React\EventLoop\Factory::create();
        $socket = new \React\Socket\Server($loop);
        $http = new \React\Http\Server($socket, $loop);

        $http->on('request', $this->serveWebContent());
        $this->output->writeln(
            "Server running at http://127.0.0.1:{$this->port}"
        );

        $socket->listen($this->port);
        $loop->run();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function serveWebContent()
    {
        return function ($request, $response) {
            if (is_file("public/{$request->getPath()}")) {
                return $this->serveAssetsFile($request, $response);
            } elseif ($request->getPath() === '/build') {
                $build = new Build(
                    $this->config['commands'],
                    $this->config->toArray()
                );
                $build->run();
                $this->buildQueue->enqueue($build);
            }
            return $this->serveIndexFile($response);
        };
    }

    protected function serveIndexFile($response)
    {
        $response->writeHead(
            // Set the status code
            200,
            // and content-type header
            array('Content-Type' => 'text/html')
        );
        $response->end($this->renderHtml());
    }

    protected function renderHtml()
    {
        return $this->twig->render(
            'index.twig',
            [
                'jobs' => $this->buildQueue,
                'config' => $this->config,
            ]
        );
    }

    protected function serveAssetsFile($request, $response)
    {
        // This code was originally stolen *directly* from Sculpin,
        // it even uses dflydev's awesome mimetype repo
        // I modified it to make it less readable

        // If the mime type repo knows about the extension, use that
        // Otherwise just use application/octet-stream
        $type = ($this->mimeTypeRepo->findType(
            pathinfo($request->getPath(), PATHINFO_EXTENSION)
        )) ?: 'application/octet-stream';

        $response->writeHead(200, ["Content-Type" => $type]);
        $response->end(
            file_get_contents("public/{$request->getPath()}")
        );

    }
}
