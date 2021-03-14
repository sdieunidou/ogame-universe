<?php

namespace App\OGame;

final class OGameEndpoint
{
    // SERVER
    const SERVER = 'https://s%s-%s.ogame.gameforge.com/api/serverData.xml';

    // PLAYER
    const PLAYERS = 'https://s%s-%s.ogame.gameforge.com/api/players.xml';
    const PLAYER = 'https://s%s-%s.ogame.gameforge.com/api/playerData.xml?id={userId}';

    const PLAYERS_RANK_POINTS = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=0';
    const PLAYERS_RANK_ECO = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=1';
    const PLAYERS_RANK_TECHNOLOGY = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=2';
    const PLAYERS_RANK_MILITARY = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=3';
    const PLAYERS_RANK_MILITARY_BUILT = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=5';
    const PLAYERS_RANK_MILITARY_DESTROYED = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=6';
    const PLAYERS_RANK_MILITARY_LOST = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=4';
    const PLAYERS_RANK_MILITARY_HONOR = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=1&type=7';

    // ALLIANCE
    const ALLIANCES = 'https://s%s-%s.ogame.gameforge.com/api/alliances.xml';

    const ALLIANCES_RANK_POINTS = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=0';
    const ALLIANCES_RANK_ECO = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=1';
    const ALLIANCES_RANK_TECHNOLOGY = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=2';
    const ALLIANCES_RANK_MILITARY = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=3';
    const ALLIANCES_RANK_MILITARY_BUILT = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=5';
    const ALLIANCES_RANK_MILITARY_DESTROYED = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=6';
    const ALLIANCES_RANK_MILITARY_LOST = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=4';
    const ALLIANCES_RANK_MILITARY_HONOR = 'https://s%s-%s.ogame.gameforge.com/api/highscore.xml?category=2&type=7';

    // GALAXY
    const UNIVERSE = 'https://s%s-%s.ogame.gameforge.com/api/universe.xml';

    static public function buildUrl(string $url, int $universeId, string $lang): string
    {
        return sprintf($url, $universeId, $lang);
    }
}
