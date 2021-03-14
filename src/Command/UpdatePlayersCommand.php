<?php

namespace App\Command;

use App\Entity\Player;
use App\OGame\API\PlayerAPI;
use App\Repository\AllianceRepository;
use App\Repository\PlayerRepository;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdatePlayersCommand extends Command
{
    protected static $defaultName = 'api:update-players';

    private $entityManager;

    private $serverRepository;

    private $playerAPI;

    private $playerRepository;

    private $allianceRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ServerRepository $serverRepository,
        PlayerAPI $playerAPI,
        PlayerRepository $playerRepository,
        AllianceRepository $allianceRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->serverRepository = $serverRepository;
        $this->playerAPI = $playerAPI;
        $this->playerRepository = $playerRepository;
        $this->allianceRepository = $allianceRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $servers = $this->serverRepository->findAll();

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Starting to update players data (%d servers)', \count($servers)));

        foreach ($servers as $server) {
            $data = $this->playerAPI->getPlayers($server);
            $io->comment(sprintf('%d players returned by the OGame API on %s', \count($data), $server->getName()));

            foreach ($data as $row) {
                if (null === $player = $this->playerRepository->findOneBy([
                        'server' => $server->getId(),
                        'ogameId' => $row[0],
                    ])) {
                    $player = new Player();
                }

                $player
                    ->setServer($server)
                    ->setOgameId($row[0])
                    ->setName($row[1])
                    ->setStatus($row[2]?:Player::STATUS_ACTIVE)
                    ->setAlliance(null)
                ;

                if (!empty($row[3])) {
                    $alliance = $this->allianceRepository->findOneBy([
                        'server' => $server->getId(),
                        'ogameId' => $row[3],
                    ]);

                    if ($alliance) {
                        $player->setAlliance($alliance);
                    }
                }

                if (null === $player->getId()) {
                    $this->entityManager->persist($player);
                }
            }

            $server->setLatestPlayerUpdate(new \DateTimeImmutable());

            $this->entityManager->flush();
        }

        $io->success('Players successfully updated');
        return Command::SUCCESS;
    }
}
