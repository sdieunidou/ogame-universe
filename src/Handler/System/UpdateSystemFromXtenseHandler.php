<?php

namespace App\Handler\System;

use App\Entity\Planet;
use App\Entity\PlanetActivity;
use App\Entity\Player;
use App\Entity\Server;
use App\OGame\Helper;
use App\Repository\AllianceRepository;
use App\Repository\PlanetRepository;
use App\Repository\PlayerRepository;
use App\Xtense\Exception\XtenseException;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateSystemFromXtenseHandler
{
    private $entityManager;

    private $planetRepository;

    private $playerRepository;

    private $allianceRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PlanetRepository $planetRepository,
        PlayerRepository $playerRepository,
        AllianceRepository $allianceRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->planetRepository = $planetRepository;
        $this->playerRepository = $playerRepository;
        $this->allianceRepository = $allianceRepository;
    }

    public function __invoke(Server $server, array $raw)
    {
        $galaxy = (int) $raw['galaxy'];
        $system = (int) $raw['system'];

        if (empty($galaxy) || empty($system)) {
            throw new XtenseException('galaxy or/and system and missing');
        }

        $now = new \DateTimeImmutable();

        foreach ($raw['rows'] as $position => $data) {
            if ($position < 1 || $position > 15) {
                continue;
            }

            $coords = sprintf('%d:%d:%d', $galaxy, $system, $position);

            if (empty($data) || empty($data['planet_id']) || empty($data['player_id'])) {
                // check if a planet exist in DB, maybe destroyed
                $oldPlanet = $this->planetRepository->getPlanetOfCoordinates($server, $coords);
                if ($oldPlanet instanceof Planet) {
                    $this->entityManager->remove($oldPlanet);
                }

                continue;
            }

            $player = $this->playerRepository->findOneBy([
                'server' => $server->getId(),
                'ogameId' => (int) $data['player_id'],
            ]);

            if (!$player instanceof Player) {
                $player = (new Player())
                    ->setServer($server)
                    ->setOgameId((int) $data['player_id'])
                    ->setName($data['player_name'])
                    ->setStatus($data['status']?:Player::STATUS_ACTIVE)
                ;

                $allianceId = (int) $data['ally_id'];
                if (!empty($allianceId) && $allianceId > 0) {
                    $alliance = $this->allianceRepository->findOneBy([
                        'server' => $server->getId(),
                        'ogameId' => $allianceId,
                    ]);

                    if ($alliance) {
                        $player->setAlliance($alliance);
                    }
                }

                $this->entityManager->persist($player);
            }

            $planet = $this->planetRepository->getPlanetOfOgameIdAndPlayerId($server, (int) $data['planet_id'], $player->getOgameId());
            if (!$planet instanceof Planet) {
                // check if a planet exist in DB for another player, and remove it
                $oldPlanet = $this->planetRepository->getPlanetOfCoordinates($server, $coords);
                if ($oldPlanet instanceof Planet) {
                    $this->entityManager->remove($oldPlanet);
                }

                $planet = (new Planet())
                    ->setServer($server)
                    ->setPlayer($player)
                    ->setOgameId((int) $data['planet_id'])
                    ->setName($data['planet_name'])
                    ->setCoordinates($coords)
                    ->setGalaxy($galaxy)
                    ->setSystem($system)
                    ->setPosition($position)
                    ->setHasMoon((bool) $data['moon'])
                    ->setMoonOgameId($data['moon_id'] !== '' ? $data['moon_id'] : null)
                    ->setMoonName('moon')
                ;

                $this->entityManager->persist($planet);
            }

            $planet->setDebrisMetal($data['debris']['metal']);
            $planet->setDebrisCrystal($data['debris']['cristal']);

            if (is_numeric($data['activity'])) {
                if ($data['activity'] === -1) {
                    $planet->setActivity(null);
                    $planet->setActivityAt(null);
                } elseif ($data['activity'] < 15) {
                    $planet->setActivity($data['activity']);
                    $planet->setActivityAt($now);
                } else {
                    $activityAt = new \DateTime(sprintf('-%d minutes', $data['activity']));
                    $planet->setActivity(Helper::getActivity($activityAt));
                    $planet->setActivityAt($activityAt);
                }

                if (null !== $planet->getActivity()) {
                    $planetActivity = (new PlanetActivity())
                        ->setPlanet($planet)
                        ->setActivity($planet->getActivity())
                        ->setActivityAt($planet->getActivityAt())
                        ->setDebrisMetal($data['debris']['metal'])
                        ->setDebrisCrystal($data['debris']['cristal']);

                    $planet->addActivity($planetActivity);
                }
            }

            if (is_numeric($data['activityMoon'])) {
                if ($data['activityMoon'] === -1) {
                    $planet->setMoonActivity(null);
                    $planet->setMoonActivityAt(null);
                } elseif ($data['activityMoon'] < 15) {
                    $planet->setMoonActivity($data['activityMoon']);
                    $planet->setMoonActivityAt($now);
                } else {
                    $activityAt = new \DateTime(sprintf('-%d minutes', $data['activityMoon']));
                    $planet->setMoonActivity(Helper::getActivity($activityAt));
                    $planet->setMoonActivityAt($activityAt);
                }

                if (null !== $planet->getMoonActivity()) {
                    $moonActivity = (new PlanetActivity())
                        ->setPlanet($planet)
                        ->setMoonActivity($planet->getMoonActivity())
                        ->setMoonActivityAt($planet->getMoonActivityAt())
                        ->setDebrisMetal($data['debris']['metal'])
                        ->setDebrisCrystal($data['debris']['cristal'])
                    ;

                    $planet->addActivity($moonActivity);
                }
            }

            $planet->setLatestXtenseReportAt($now);
        }

        $this->entityManager->flush();
    }
}
