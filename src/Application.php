<?php

namespace App;

use GuzzleHttp\Client;
use League\Flysystem\Filesystem;

class Application
{
    private $client;
    private $filesystem;

    public function __construct(Client $client, Filesystem $filesystem)
    {
        $this->client = $client;
        $this->filesystem = $filesystem;
    }

    public function getLinksCollection($slug)
    {
        $crawler = (new Crawler($this->client))->getCrawler($slug);
        $links = $crawler->filter('div.container ul.episode-list')
            ->children('li')
            ->each(function ($item) {
                return $item->filter('p.episode-list-meta > a')->extract(['href'])[0];
            });

        return collect($links);
    }

    public function prepare($slug): array
    {
        $seriesPath = str_replace('/series/', '', $slug);
        $dir = getenv('DOWNLOAD_DIR') . DIRECTORY_SEPARATOR . $seriesPath;

        if ($this->filesystem->has($seriesPath)) {
            $this->filesystem->deleteDir($seriesPath);
        }

        $this->filesystem->createDir($seriesPath);

        return [$dir, $seriesPath];
    }

    public function download()
    {
        return new Download($this->client, $this->filesystem);
    }

    public function login(): void
    {
        (new Login())->login($this->client);
    }
}
