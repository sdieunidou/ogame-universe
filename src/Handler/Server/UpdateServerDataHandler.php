<?php

namespace App\Handler\Server;

use App\Entity\Server;
use App\EventSubscriber\CurrentServerSubscriber;
use App\OGame\API\ServerAPI;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class UpdateServerDataHandler
{
    private $serverApi;

    private $entityManager;

    public function __construct(
        ServerAPI $serverAPI,
        EntityManagerInterface $entityManager
    )
    {
        $this->serverApi = $serverAPI;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Server $server): Server
    {
        $serverData = $this->serverApi->getServer($server);

        $server
            ->setName($serverData['name'])
            ->setServerId($serverData['number'])
            ->setLanguage($serverData['language'])
            ->setGalaxies($serverData['galaxies'])
            ->setSystems($serverData['systems'])
            ->setDonutGalaxy((bool) $serverData['donutGalaxy'])
            ->setDonutSystem((bool) $serverData['donutSystem'])
            ->setSpeed($serverData['speed'])
            ->setSpeedFleet($serverData['speedFleet'])
        ;

        $this->entityManager->flush();

        return $server;
    }
}
