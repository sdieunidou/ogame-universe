<?php

namespace App\OGame\API;

use App\Entity\Server;
use App\OGame\OGameEndpoint;
use Symfony\Component\DomCrawler\Crawler;

class PlayerAPI
{
    private $ogameAPI;

    public function __construct(OGameAPI $ogameAPI)
    {
        $this->ogameAPI = $ogameAPI;
    }

    public function getPlayers(Server $server): array
    {
        $response = $this->ogameAPI->get(
            OGameEndpoint::buildUrl(OGameEndpoint::PLAYERS, $server->getServerId(), $server->getLanguage())
        );

        $crawler = new Crawler($response->getContent());

        return $crawler->filterXPath('//players/*')
            ->extract(['id', 'name', 'status', 'alliance'])
        ;
    }
}
