<?php

namespace App;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as Scrapper;

class Crawler
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getBody($slug): string
    {
        $data = $this->client->get($slug)->getBody();

        return (string) $data;
    }

    public function getCrawler($slug): Scrapper
    {
        $content = $this->getBody($slug);

        return new Scrapper($content);
    }

    public function checkExists($slug)
    {
        $data = $this->client->get('/api/series')->getBody();
        $series = \GuzzleHttp\json_decode((string) $data, true);

        $flatSeries = collect($series)
            ->flatMap(function ($items) {
                return array_map(function ($item) {
                    return $item;
                }, $items);
            })
            ->reject(function ($item) use ($slug) {
                return $item['path'] !== $slug;
            });

        return $flatSeries->isNotEmpty();
    }
}
