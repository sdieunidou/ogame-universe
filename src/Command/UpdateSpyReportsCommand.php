<?php

namespace App\Command;

use App\Handler\Spy\AddSpyReportHandler;
use App\Repository\SpyRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateSpyReportsCommand extends Command
{
    protected static $defaultName = 'api:update-reports';

    private $spyRepository;

    private $addSpyReportHandler;

    public function __construct(
        SpyRepository $spyRepository,
        AddSpyReportHandler $addSpyReportHandler
    ) {
        $this->spyRepository = $spyRepository;
        $this->addSpyReportHandler = $addSpyReportHandler;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reports = $this->spyRepository->findAll();

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Starting to update %d reports', \count($reports)));

        foreach ($reports as $report) {
            ($this->addSpyReportHandler)($report->getServer(), $report->getApiKey());
        }

        $io->success('Reports successfully updated');
        return Command::SUCCESS;
    }
}
