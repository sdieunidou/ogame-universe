<?php

namespace App\Twig;

use App\Entity\Planet;
use App\Entity\Server;
use App\Entity\Spy;
use App\Repository\SpyRepository;
use App\Server\CurrentServerResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SpyExtension extends AbstractExtension
{
    private $serverResolver;

    private $spyRepository;

    public function __construct(
        CurrentServerResolver $serverResolver,
        SpyRepository $spyRepository
    )
    {
        $this->serverResolver = $serverResolver;
        $this->spyRepository = $spyRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('latest_spy_report_of', [$this, 'getLatestSpyReportOf']),
        ];
    }

    public function getLatestSpyReportOf(string $coordinates, bool $isMoon = false): ?Spy
    {
        return $this->spyRepository->getLatestSpyReportOf($this->serverResolver->getCurrentServer(), $coordinates, $isMoon);
    }
}
