<?php

namespace App\OGame\API;

use App\Entity\Server;
use App\OGame\OGameEndpoint;
use Symfony\Component\DomCrawler\Crawler;

class ScoresAPI
{
    private $ogameAPI;

    public function __construct(OGameAPI $ogameAPI)
    {
        $this->ogameAPI = $ogameAPI;
    }

    public function getScores(Server $server, string $scoreType = OGameEndpoint::PLAYERS_RANK_POINTS): array
    {
        $response = $this->ogameAPI->get(
            OGameEndpoint::buildUrl($scoreType, $server->getServerId(), $server->getLanguage())
        );

        $crawler = new Crawler($response->getContent());

        return $crawler->filterXPath('//highscore/*')
            ->extract(['id', 'position', 'score', 'ships'])
        ;
    }
}
