<?php

namespace App\Command;

use App\Entity\Alliance;
use App\Entity\Planet;
use App\Entity\Player;
use App\OGame\API\AllianceAPI;
use App\OGame\API\ScoresAPI;
use App\OGame\API\UniverseAPI;
use App\OGame\OGameEndpoint;
use App\Repository\AllianceRepository;
use App\Repository\PlanetRepository;
use App\Repository\PlayerRepository;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateScoresCommand extends Command
{
    protected static $defaultName = 'api:update-scores';

    private $entityManager;

    private $serverRepository;

    private $scoresAPI;

    private $playerRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ServerRepository $serverRepository,
        ScoresAPI $scoresAPI,
        PlayerRepository $playerRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->serverRepository = $serverRepository;
        $this->scoresAPI = $scoresAPI;
        $this->playerRepository = $playerRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $servers = $this->serverRepository->findAll();

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Starting to update scores data (%d servers)', \count($servers)));

        foreach ($servers as $server) {
            $io->comment(sprintf('Updating scores of %s', $server->getName()));

            $allScores = [
                'score' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_POINTS),
                'economyScore' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_ECO),
                'researchScore' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_TECHNOLOGY),
                'militaryScore' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_MILITARY),
                'militaryBuiltScore' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_MILITARY_BUILT),
                'militaryDestroyedScore' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_MILITARY_DESTROYED),
                'militaryLostScore' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_MILITARY_LOST),
                'militaryHonorScore' => $this->scoresAPI->getScores($server, OGameEndpoint::PLAYERS_RANK_MILITARY_HONOR),
            ];

            foreach ($allScores as $type => $data) {
                foreach ($data as $row) {
                    if (null === $player = $this->playerRepository->findOneBy([
                            'server' => $server->getId(),
                            'ogameId' => $row[0],
                        ])) {
                        $io->error(sprintf('Player with ID %d not found on server %s', $row[0], $server->getId()));
                        continue;
                    }

                    $player
                        ->{sprintf('set%s', ucfirst($type))}((int) $row[2])
                    ;

                    switch ($type) {
                        case 'militaryScore':
                            $player->setMilitaryShipsScore((int) $row[3]);
                            break;

                        case 'score':
                            $dt = new \DateTimeImmutable();
                            if ($dt->diff($player->getDateOfScoreAt24H())->days > 0) {
                                $player->setScoreAt24H((int) $row[2]);
                                $player->setDateOfScoreAt24H(new \DateTimeImmutable());
                            }
                            break;
                    }
                }

                $this->entityManager->flush();
            }

            $server->setLatestRankingUpdate(new \DateTimeImmutable());
            $this->entityManager->flush();
        }

        $io->success('Scores successfully updated');
        return Command::SUCCESS;
    }
}
