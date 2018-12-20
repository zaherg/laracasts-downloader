<?php

namespace App\Commands;

use App\Crawler;
use App\Application;
use GuzzleHttp\Client;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Download extends Command
{
    private $client;
    private $seriesPath;
    private $dir;

    public function __construct(Client $client, Filesystem $filesystem, ?string $name = null)
    {
        parent::__construct($name);
        $this->client = $client;
        $this->filesystem = $filesystem;
    }

    public function configure()
    {
        $this->setName('download')
            ->setProcessTitle('Laracasts Downloader')
            ->addArgument('series', InputArgument::IS_ARRAY, 'The series slug value')
            ->setDescription('This command will download the courses you specify');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $app = new Application($this->client, $this->filesystem);

        foreach ($input->getArgument('series') as $course) {
            $io->section('Downloading ' . $course);

            $slug = sprintf('/series/%s', $course);

            $exists = (new Crawler($this->client))->checkExists($slug);

            if (! $exists) {
                $io->error('Please make sure that the series exists');
                exit;
            }

            [$this->dir, $this->seriesPath] = $app->prepare($slug);

            $app->login();
            $download = $app->download();

            $links = $app->getLinksCollection($slug);
            $download->getItems($links, $io, $this->dir, $this->seriesPath);
        }
    }
}
