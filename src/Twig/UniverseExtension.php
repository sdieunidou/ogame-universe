<?php

namespace App\Twig;

use App\Entity\Alliance;
use App\Entity\Player;
use App\OGame\Helper;
use App\Repository\PlanetRepository;
use App\Server\CurrentServerResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UniverseExtension extends AbstractExtension
{
    private $serverResolver;

    private $planetRepository;

    public function __construct(
        CurrentServerResolver $serverResolver,
        PlanetRepository $planetRepository
    )
    {
        $this->serverResolver = $serverResolver;
        $this->planetRepository = $planetRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_activity', [$this, 'getActivity']),
            new TwigFunction('has_activity', [$this, 'hasActivity']),
            new TwigFunction('universe_galaxy', [$this, 'universeGalaxy']),
            new TwigFunction('universe_system', [$this, 'universeSystem']),
            new TwigFunction('player_galaxies', [$this, 'getPlayerGalaxies']),
            new TwigFunction('planets_of_alliance_of_galaxy', [$this, 'getPlanetsOfAllianceOfGalaxy']),
        ];
    }

    public function universeGalaxy(int $system): int
    {
        return Helper::checkGalaxyNumber($this->serverResolver->getCurrentServer(), $system);
    }

    public function universeSystem(int $system): int
    {
        return Helper::checkSystemNumber($this->serverResolver->getCurrentServer(), $system);
    }

    public function getPlayerGalaxies(Player $player): array
    {
        return $this->planetRepository->getGalaxiesOfPlayer($this->serverResolver->getCurrentServer(), $player);
    }

    public function getPlanetsOfAllianceOfGalaxy(Alliance $alliance, int $galaxy): array
    {
        return $this->planetRepository->getPlanetsOfAllianceOfGalaxy($this->serverResolver->getCurrentServer(), $alliance, $galaxy);
    }

    public function hasActivity(?\DateTimeInterface $activityAt): bool
    {
        return Helper::hasActivity($activityAt);
    }

    public function getActivity(?\DateTimeInterface $activityAt): ?int
    {
        return Helper::getActivity($activityAt);
    }
}
