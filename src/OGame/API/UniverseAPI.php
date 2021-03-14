<?php

namespace App\OGame\API;

use App\Entity\Server;
use App\OGame\Helper;
use App\OGame\OGameEndpoint;
use Symfony\Component\DomCrawler\Crawler;

class UniverseAPI
{
    private $ogameAPI;

    public function __construct(OGameAPI $ogameAPI)
    {
        $this->ogameAPI = $ogameAPI;
    }

    public function getUniverse(Server $server): array
    {
        $response = $this->ogameAPI->get(
            OGameEndpoint::buildUrl(OGameEndpoint::UNIVERSE, $server->getServerId(), $server->getLanguage())
        );

        $crawler = new Crawler($response->getContent());

        $planets = $crawler->filterXPath('//universe/*')->each(function (Crawler $planetCrawler, $i) {
            $moonCrawler = $planetCrawler->filterXPath('node()/moon');

            $coordinates = $planetCrawler->attr('coords');
            $parsedCoordinates = Helper::parseCoordinates($coordinates);

            return [
                'name' => $planetCrawler->attr('name'),
                'id' => (int) $planetCrawler->attr('id'),
                'playerId' => (int) $planetCrawler->attr('player'),
                'coords' => $coordinates,
                'position' => (int) $parsedCoordinates[2],
                'system' => (int) $parsedCoordinates[1],
                'galaxy' => (int) $parsedCoordinates[0],
                'moon' => (bool) $moonCrawler->count(),
                'moonId' => $moonCrawler->count() ? $moonCrawler->attr('id') : null,
                'moonName' => $moonCrawler->count() ? $moonCrawler->attr('name') : null,
                'moonSize' => $moonCrawler->count() ? (int) $moonCrawler->attr('size') : null,
            ];
        });

        return $planets;
    }
}
