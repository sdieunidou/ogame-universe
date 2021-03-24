<?php

namespace App\Handler\Spy;

use App\Entity\Planet;
use App\Entity\Player;
use App\Entity\Server;
use App\Entity\Spy;
use App\OGame\DataTransformer\SpyReportDataTransformer;
use App\Repository\SpyRepository;
use Doctrine\ORM\EntityManagerInterface;

final class AddSpyReportHandler
{
    private $spyReportDataTransformer;

    private $entityManager;

    private $spyRepository;

    public function __construct(
        SpyReportDataTransformer $spyReportDataTransformer,
        EntityManagerInterface $entityManager,
        SpyRepository $spyRepository
    )
    {
        $this->spyReportDataTransformer = $spyReportDataTransformer;
        $this->entityManager = $entityManager;
        $this->spyRepository = $spyRepository;
    }

    public function __invoke(Server $server, string $apiKey): Spy
    {
        $data = $this->spyReportDataTransformer->getSpyReportOfApiKey($apiKey);
        if (!empty($data['errors'])) {
            throw new \LogicException(sprintf('This api key is not valid: %s', $data['errors'][0]['content']));
        }

        $player = $this->entityManager
            ->getRepository(Player::class)
            ->getPlayerOfId($server, $data['data']['defender']['id'])
        ;

        $planet = $this->entityManager
            ->getRepository(Planet::class)
            ->getPlanetOfCoordinates($server, $data['data']['defender']['planet']['coordinates'])
        ;

        $totalShipScore = 0;
        $totalDefenseScore = 0;

        foreach ($data['data']['defender']['defence'] as $defences) {
            $totalDefenseScore += $defences['count'] *
                ($defences['technology']['resources']['metal'] + $defences['technology']['resources']['crystal'] + $defences['technology']['resources']['deuterium']);
        }

        foreach ($data['data']['defender']['ships'] as $ships) {
            $totalShipScore += $ships['count'] *
                ($ships['technology']['resources']['metal'] + $ships['technology']['resources']['crystal'] + $ships['technology']['resources']['deuterium']);
        }

        if (!$spy = $this->spyRepository->getOfApiKey($server, $apiKey)) {
            $spy = (new Spy());
        }

        $spy = $spy
            ->setApiKey($apiKey)
            ->setServer($server)
            ->setData($data)
            ->setSpyAt(new \DateTime($data['data']['time']))

            ->setActivity($data['data']['activity'])
            ->setLootPercentage($data['data']['loot_percentage'])
            ->setTotalDefense($data['data']['total_defense_count'])
            ->setTotalShip($data['data']['total_ship_count'])
            ->setTotalShipScore($totalShipScore)
            ->setTotalDefenseScore($totalDefenseScore)

            ->setPlayer($player)
            ->setPlayerOgameId($data['data']['defender']['id'])
            ->setPlayerName($data['data']['defender']['name'])
            ->setPlayerClass($data['data']['defender']['class'])

            ->setPlanet($planet)
            ->setCoordinates($data['data']['defender']['planet']['coordinates'])
            ->setGalaxy($data['data']['defender']['planet']['galaxy'])
            ->setSystem($data['data']['defender']['planet']['system'])
            ->setPosition($data['data']['defender']['planet']['position'])
            ->setIsMoon($data['data']['defender']['planet']['type'] === Planet::TYPE_MOON)

            ->setMetal($data['data']['defender']['resources']['metal'])
            ->setCrystal($data['data']['defender']['resources']['crystal'])
            ->setDeuterium($data['data']['defender']['resources']['deuterium'])
            ->setEnergy($data['data']['defender']['resources']['energy'])
        ;

        if (null === $spy->getId()) {
            $this->entityManager->persist($spy);
        }

        $this->entityManager->flush();

        return $spy;
    }
}