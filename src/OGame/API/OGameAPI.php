<?php

namespace App\OGame\API;

use App\OGame\OGameEndpoint;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OGameAPI
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get(string $url): ResponseInterface
    {
        return $this->httpClient->request(
            'GET',
            $url
        );
    }
}
