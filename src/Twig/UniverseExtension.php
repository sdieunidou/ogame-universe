<?php

namespace App\Twig;

use App\Entity\Player;
use App\OGame\Helper;
use App\Repository\PlanetRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UniverseExtension extends AbstractExtension
{
    private $planetRepository;

    public function __construct(PlanetRepository $planetRepository)
    {
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
        return Helper::checkGalaxyNumber($system);
    }

    public function universeSystem(int $system): int
    {
        return Helper::checkSystemNumber($system);
    }

    public function playerGalaxies(Player $player): array
    {
        return $this->planetRepository->getGalaxiesOfPlayer($player);
    }
}
