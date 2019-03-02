<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;

class Download
{
    protected $client;
    protected $filesystem;

    public function __construct(Client $client, Filesystem $filesystem)
    {
        $this->client = $client;
        $this->filesystem = $filesystem;
    }

    public function getItems($links, $output, $dir, $seriesPath): void
    {
        $output->writeln(\sprintf("We are going to download <fg=red;options=bold>%d</> files.\n", $links->count()));

        $links->transform(function ($item) {
            $crawler = (new Crawler($this->client))->getCrawler($item);
            $link = $crawler
                    ->filter('div.section > div.container')
                    ->each(function ($div) {
                        return $div->filter('a[title="Download Video"]')->extract(['href']);
                    });

            return $link;
        })
            ->flatten()
            ->filter(function ($link) {
                return Str::contains($link, 'downloads');
            })->each(function ($item) use ($output, $dir, $seriesPath): void {
                $this->get($item, $output, $dir, $seriesPath);
            });
    }

    private function get($link, $output, $dir, $seriesPath): void
    {
        $fileName = Str::random(10) . '.mp4';

        $progress = new ProgressBar($output);
        $progress->setFormat("%status% : %current%/%max%  [%bar%] %percent:3s%% Mem: %memory:6s%\n");
        $progress->setMessage('Gathering course data', 'status');

        $file = $this->client
            ->request('GET', $link, [
                'sink' => fopen($dir . DIRECTORY_SEPARATOR . $fileName, 'wb'),
                'progress' => function ($downloadTotal, $downloadedBytes, $uploadTotal, $uploadedBytes) use ($progress): void {
                    $progress->setMaxSteps($downloadTotal);
                    $progress->start();

                    $progress->advance($downloadedBytes);

                    $progress->setMessage('Downloading', 'status');
                    $progress->display();

                    if ($downloadTotal === $downloadedBytes) {
                        $progress->finish();
                    }
                },
            ])->getHeader('Content-Disposition');

        $file = str_replace('"', '', explode('=', explode(';', $file[0])[1])[1]);

        $this->filesystem->rename($seriesPath . DIRECTORY_SEPARATOR . $fileName, $seriesPath . DIRECTORY_SEPARATOR . $file);

        $message = sprintf('<fg=green;options=bold>[Success]</>: file <fg=green;options=bold,underscore>%s</> has been downloaded successfully.', basename($file));

        $output->writeln($message . "\n");
    }
}
