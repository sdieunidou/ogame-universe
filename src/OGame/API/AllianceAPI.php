<?php

namespace App\OGame\API;

use App\Entity\Server;
use App\OGame\OGameEndpoint;
use Symfony\Component\DomCrawler\Crawler;

class AllianceAPI
{
    private $ogameAPI;

    public function __construct(OGameAPI $ogameAPI)
    {
        $this->ogameAPI = $ogameAPI;
    }

    public function getAlliances(Server $server): array
    {
        $response = $this->ogameAPI->get(
            OGameEndpoint::buildUrl(OGameEndpoint::ALLIANCES, $server->getServerId(), $server->getLanguage())
        );

        $crawler = new Crawler($response->getContent());

        return $crawler->filterXPath('//alliances/*')
            ->extract(['id', 'name', 'tag', 'homepage'])
        ;
    }
}
