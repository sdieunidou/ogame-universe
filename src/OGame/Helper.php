<?php

namespace App\OGame;

class Helper
{
    public static function parseCoordinates(string $coordinates): array
    {
        return explode(':', $coordinates);
    }
}