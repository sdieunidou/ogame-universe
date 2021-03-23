<?php

namespace App\Command;

use App\Entity\Alliance;
use App\Entity\Planet;
use App\Entity\Player;
use App\OGame\API\AllianceAPI;
use App\OGame\API\UniverseAPI;
use App\Repository\AllianceRepository;
use App\Repository\PlanetRepository;
use App\Repository\PlayerRepository;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateUniverseCommand extends Command
{
    protected static $defaultName = 'api:update-universe';

    private $entityManager;

    private $serverRepository;

    private $universeAPI;

    private $planetRepository;

    private $playerRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ServerRepository $serverRepository,
        UniverseAPI $universeAPI,
        PlanetRepository $planetRepository,
        PlayerRepository $playerRepository
    ) {
        $this->entityManager = $entityManager;
        $this->serverRepository = $serverRepository;
        $this->universeAPI = $universeAPI;
        $this->planetRepository = $planetRepository;
        $this->playerRepository = $playerRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $servers = $this->serverRepository->findAll();

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Starting to update universes data (%d servers)', \count($servers)));

        foreach ($servers as $server) {
            $data = $this->universeAPI->getUniverse($server);
            $io->comment(sprintf('%d planets returned by the OGame API', \count($data)));

            $this->planetRepository->deleteAllOfServer($server);

            foreach ($data as $row) {
                if (null === $planet = $this->planetRepository->findOneBy([
                        'server' => $server->getId(),
                        'ogameId' => $row['id'],
                    ])) {
                    $planet = new Planet();
                }

                $player = $this->playerRepository->findOneBy([
                    'server' => $server->getId(),
                    'ogameId' => $row['playerId'],
                ]);

                if (!$player instanceof Player) {
                    $io->error(sprintf('Player with ID %d not found on server %s', $row['playerId'], $server->getId()));
                    continue;
                }

                $planet
                    ->setServer($server)
                    ->setPlayer($player)
                    ->setOgameId($row['id'])
                    ->setName($row['name'])
                    ->setCoordinates($row['coords'])
                    ->setPosition($row['position'])
                    ->setSystem($row['system'])
                    ->setGalaxy($row['galaxy'])
                    ->setHasMoon($row['moon'])
                    ->setMoonOgameId($row['moonId'])
                    ->setMoonName($row['moonName'])
                    ->setMoonSize($row['moonSize'])
                ;

                if (null === $planet->getId()) {
                    $this->entityManager->persist($planet);
                }
            }

            $server->setLatestUniverseUpdate(new \DateTimeImmutable());

            $this->entityManager->flush();
        }

        $io->success('Universes successfully updated');
        return Command::SUCCESS;
    }
}
