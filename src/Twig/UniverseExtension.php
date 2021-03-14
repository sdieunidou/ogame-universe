<?php

namespace App\Twig;

use App\OGame\Helper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UniverseExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('universe_galaxy', [$this, 'universeGalaxy']),
            new TwigFunction('universe_system', [$this, 'universeSystem']),
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
}
