<?php

namespace App\Command;

use App\Entity\Alliance;
use App\OGame\API\AllianceAPI;
use App\Repository\AllianceRepository;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateAlliancesCommand extends Command
{
    protected static $defaultName = 'api:update-alliances';

    private $entityManager;

    private $serverRepository;

    private $allianceAPI;

    private $allianceRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ServerRepository $serverRepository,
        AllianceAPI $allianceAPI,
        AllianceRepository $allianceRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->serverRepository = $serverRepository;
        $this->allianceAPI = $allianceAPI;
        $this->allianceRepository = $allianceRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $servers = $this->serverRepository->findAll();

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Starting to update alliances data (%d servers)', \count($servers)));

        foreach ($servers as $server) {
            $data = $this->allianceAPI->getAlliances($server);
            $io->comment(sprintf('%d alliances returned by the OGame API on %s', \count($data), $server->getName()));

            foreach ($data as $row) {
                if (null === $alliance = $this->allianceRepository->findOneBy([
                        'server' => $server->getId(),
                        'ogameId' => $row[0],
                    ])) {
                    $alliance = new Alliance();
                }

                $alliance
                    ->setServer($server)
                    ->setOgameId($row[0])
                    ->setName($row[1])
                    ->setTag($row[2])
                    ->setWebsite($row[3]?:null)
                ;

                if (null === $alliance->getId()) {
                    $this->entityManager->persist($alliance);
                }
            }

            $server->setLatestAllianceUpdate(new \DateTimeImmutable());

            $this->entityManager->flush();
        }

        $io->success('Alliances successfully updated');
        return Command::SUCCESS;
    }
}
