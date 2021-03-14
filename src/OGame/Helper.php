<?php

namespace App\OGame;

class Helper
{
    public static function parseCoordinates(string $coordinates): array
    {
        return explode(':', $coordinates);
    }

    public static function checkGalaxyNumber(int $galaxy): int
    {
        if ($galaxy > 5) {
            $galaxy = 5;
        } elseif ($galaxy < 1) {
            $galaxy = 1;
        }

        return $galaxy;
    }

    public static function checkSystemNumber(int $system): int
    {
        if ($system > 450) {
            $system = 1;
        } elseif ($system < 1) {
            $system = 450;
        }

        return $system;
    }
}