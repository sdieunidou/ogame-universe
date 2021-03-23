<?php

namespace App\OGame;

use App\Entity\Server;

class Helper
{
    public static function parseCoordinates(string $coordinates): array
    {
        return explode(':', $coordinates);
    }

    public static function checkGalaxyNumber(Server $server, int $galaxy): int
    {
        if ($galaxy > $server->getGalaxies()) {
            $galaxy = $server->getGalaxies();
        } elseif ($galaxy < 1) {
            $galaxy = 1;
        }

        return $galaxy;
    }

    public static function checkSystemNumber(Server $server, int $system): int
    {
        if ($system > $server->getSystems()) {
            $system = 1;
        } elseif ($system < 1) {
            $system = $server->getSystems();
        }

        return $system;
    }
}
