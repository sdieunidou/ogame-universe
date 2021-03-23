<?php

namespace App\OGame\API;

use App\Entity\Server;
use App\OGame\Helper;
use App\OGame\OGameEndpoint;
use Symfony\Component\DomCrawler\Crawler;

class ServerAPI
{
    private $ogameAPI;

    public function __construct(OGameAPI $ogameAPI)
    {
        $this->ogameAPI = $ogameAPI;
    }

    public function getServer(Server $server): array
    {
        $response = $this->ogameAPI->get(
            OGameEndpoint::buildUrl(OGameEndpoint::SERVER, $server->getServerId(), $server->getLanguage())
        );

        $crawler = new Crawler($response->getContent());
        $extracted = $crawler->filterXPath('//serverData/*')->each(function (Crawler $currentCrawler, $i) {
            return [
                $currentCrawler->nodeName() => $currentCrawler->text()
            ];
        });

        $serverData = [];
        foreach ($extracted as $data) {
            foreach ($data as $name => $value) {
                $serverData[$name] = is_numeric($value) ? (int) $value : $value;
            }
        }

        return $serverData;
    }
}
