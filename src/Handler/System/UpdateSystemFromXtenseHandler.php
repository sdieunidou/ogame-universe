<?php

namespace App\Handler\System;

use App\Entity\Planet;
use App\Entity\Player;
use App\Entity\Server;
use App\Repository\PlanetRepository;
use App\Xtense\Exception\XtenseException;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateSystemFromXtenseHandler
{
    private $entityManager;

    private $planetRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PlanetRepository $planetRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->planetRepository = $planetRepository;
    }

    public function __invoke(Server $server, array $data)
    {
        $galaxy = (int) $data['galaxy'];
        $system = (int) $data['system'];

        if (empty($galaxy) || empty($system)) {
            throw new XtenseException('galaxy or/and system and missing');
        }

        $now = new \DateTimeImmutable();

        foreach ($data['rows'] as $position => $data) {
            $position += 1;
            $coords = sprintf('%d:%d:%d', $galaxy, $system, $position);

            if (empty($data) || empty($data['planet_id']) || empty($data['player_id'])) {
                continue;
            }

            $player = $this->playerRepository->findOneBy([
                'server' => $server->getId(),
                'ogameId' => (int) $data['player_id'],
            ]);

            if (!$player instanceof Player) {
                // todo: add the new player
                continue;
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

            if ($data['activity'] === -1) {
                $planet->setActivityAt($now);
            } elseif (is_numeric($data['activity'])) {
                $activityAt = new \DateTime(sprintf('-%d minutes', $data['activity']));
                $planet->setActivityAt($activityAt);
            }

            if ($planet->getHasMoon() && $data['activityMoon'] === -1) {
                $planet->setMoonActivityAt($now);
            } elseif (is_numeric($data['activityMoon'])) {
                $activityAt = new \DateTime(sprintf('-%d minutes', $data['activityMoon']));
                $planet->setMoonActivityAt($activityAt);
            }
        }

        $this->entityManager->flush();
    }
}
/*
JSON Example :
{
  "galaxy": "5",
  "system": "396",
  "rows": [
    null,
    null,
    null,
    {
      "player_id": 106616,
      "planet_name": "Bamby",
      "planet_id": "33686227",
      "moon_id": "33686233",
      "moon": 1,
      "player_name": "Simpson",
      "status": "vI",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": "110712",
      "planet_name": "Colonie",
      "planet_id": "33716829",
      "moon_id": "",
      "moon": 0,
      "player_name": "trakiss",
      "status": "",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": 0,
      "activityMoon": -1
    },
    {
      "player_id": 109604,
      "planet_name": "Grand Line",
      "planet_id": "33706781",
      "moon_id": "",
      "moon": 0,
      "player_name": "El Conquistador",
      "status": "vI",
      "ally_id": 1874,
      "ally_tag": "RedA...",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": 110635,
      "planet_name": "Cerberos",
      "planet_id": "33716232",
      "moon_id": "",
      "moon": 0,
      "player_name": "driss",
      "status": "vI",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": 105010,
      "planet_name": "Ruxavel",
      "planet_id": "33675718",
      "moon_id": "",
      "moon": 0,
      "player_name": "Karnal07",
      "status": "vI",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": "110712",
      "planet_name": "Colonie",
      "planet_id": "33716828",
      "moon_id": "",
      "moon": 0,
      "player_name": "trakiss",
      "status": "",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": 0,
      "activityMoon": -1
    },
    {
      "player_id": 107716,
      "planet_name": "Krypton",
      "planet_id": "33693238",
      "moon_id": "",
      "moon": 0,
      "player_name": "samlebourrin",
      "status": "vI",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": 106219,
      "planet_name": "Iscandar",
      "planet_id": "33683730",
      "moon_id": "33683732",
      "moon": 1,
      "player_name": "tristantun",
      "status": "vI",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": 134355,
      "planet_name": "planète mère",
      "planet_id": "35289968",
      "moon_id": "",
      "moon": 0,
      "player_name": "Renegade Zibal",
      "status": "bvI",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": 110624,
      "planet_name": "Gimini",
      "planet_id": "33716100",
      "moon_id": "",
      "moon": 0,
      "player_name": "Shlomi",
      "status": "bvI",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    {
      "player_id": 113020,
      "planet_name": "Cr_Halley",
      "planet_id": "33725602",
      "moon_id": "",
      "moon": 0,
      "player_name": "alan.22",
      "status": "I",
      "ally_id": "0",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": -1,
      "activityMoon": -1
    },
    null,
    null,
    {
      "player_id": "",
      "planet_name": "Black Hole",
      "planet_id": "",
      "moon_id": "",
      "moon": 0,
      "player_name": "Lost in space",
      "status": "",
      "ally_id": "",
      "ally_tag": "",
      "debris": {
        "metal": 0,
        "cristal": 0
      },
      "activity": "",
      "activityMoon": ""
    }
  ],
}
 */