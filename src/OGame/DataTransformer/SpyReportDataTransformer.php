<?php

namespace App\OGame\DataTransformer;

use GuzzleHttp\Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpyReportDataTransformer
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getSpyReportOfApiKey(string $apiKey): array
    {
        $client = new Client();
        $response = $client->request('POST', 'https://trashsim.universeview.be/api/player', [
            'json' => [
                'key' => $apiKey,
                'party' => 'defenders',
                'fleet' => 2,
            ],
        ]);

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }
}