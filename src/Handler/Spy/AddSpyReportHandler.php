<?php

namespace App\Handler\Spy;

use App\Entity\Planet;
use App\Entity\Player;
use App\Entity\Server;
use App\Entity\Spy;
use App\OGame\DataTransformer\SpyReportDataTransformer;
use Doctrine\ORM\EntityManagerInterface;

final class AddSpyReportHandler
{
    private $spyReportDataTransformer;

    private $entityManager;

    public function __construct(
        SpyReportDataTransformer $spyReportDataTransformer,
        EntityManagerInterface $entityManager
    )
    {
        $this->spyReportDataTransformer = $spyReportDataTransformer;
        $this->entityManager = $entityManager;
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

        $spy = (new Spy())
            ->setApiKey($apiKey)
            ->setServer($server)
            ->setData($data)
            ->setSpyAt(new \DateTime($data['data']['time']))

            ->setActivity($data['data']['activity'])
            ->setLootPercentage($data['data']['loot_percentage'])
            ->setTotalDefense($data['data']['total_defense_count'])
            ->setTotalShip($data['data']['total_ship_count'])

            ->setPlayer($player)
            ->setPlayerId($data['data']['defender']['id'])
            ->setPlayerName($data['data']['defender']['name'])
            ->setPlayerClass($data['data']['defender']['class'])

            ->setPlanet($planet)
            ->setCoordinates($data['data']['defender']['planet']['coordinates'])
            ->setGalaxy($data['data']['defender']['planet']['galaxy'])
            ->setSystem($data['data']['defender']['planet']['system'])
            ->setPosition($data['data']['defender']['planet']['position'])
            ->setIsMoon($data['data']['defender']['planet']['type'] === Planet::TYPE_MOON)
        ;

        /*
        dump(
            $data,
            $spy
        );
        die;
         */

        $this->entityManager->persist($spy);
        $this->entityManager->flush();

        return $spy;
    }
}