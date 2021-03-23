<?php

namespace App\Twig;

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
            new TwigFunction('universe_galaxy', [$this, 'universeGalaxy']),
            new TwigFunction('universe_system', [$this, 'universeSystem']),
            new TwigFunction('player_galaxies', [$this, 'playerGalaxies']),
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

    public function playerGalaxies(Player $player): array
    {
        return $this->planetRepository->getGalaxiesOfPlayer($player);
    }
}
