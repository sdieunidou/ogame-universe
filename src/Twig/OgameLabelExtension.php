<?php

namespace App\Twig;

use App\Entity\Player;
use App\Entity\Server;
use App\Handler\Server\SwitchCurrentServerHandler;
use App\Repository\ServerRepository;
use App\Server\CurrentServerResolver;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OgameLabelExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ogame_label_player_class', [$this, 'getPlayerClass']),
            new TwigFunction('ogame_label_type', [$this, 'getTypeLabel']),
        ];
    }

    public function getTypeLabel(int $typeId): ?string
    {
        switch ($typeId) {
            case 1:
                return 'Metal Mine';

            case 2:
                return 'Crystal Mine';

            case 3:
                return 'Deuterium Synthesizer';

            case 4:
                return 'Solar Plant';

            case 12:
                return 'Fusion Reactor';

            case 14:
                return 'Robotics Factory';

            case 15:
                return 'Nanite Factory';

            case 22:
                return 'Metal Storage';

            case 23:
                return 'Crystal Storage';

            case 24:
                return 'Deuterium Tank';

            case 25:
                return 'Shielded Metal Den';

            case 26:
                return 'Underground Crystal Den';

            case 27:
                return 'Seabed Deuterium Den';

            case 31:
                return 'Research Lab';

            case 34:
                return 'Alliance Depot';

            case 33:
                return 'Terraformer';

            case 36:
                return 'Space Dock';

            case 41:
                return 'Lunar Base';

            case 42:
                return 'Sensor Phalanx';

            case 43:
                return 'Jump Gate';

            case 44:
                return 'Missile Silo';

            case 401:
                return 'Rocket Launcher';

            case 402:
                return 'Light Laser';

            case 403:
                return 'Heavy Laser';

            case 404:
                return 'Gauss Cannon';

            case 405:
                return 'Ion Cannon';

            case 406:
                return 'Plasma Turret';

            case 407:
                return 'Small Shield';

            case 408:
                return 'Large Shield';

            case 502:
                return 'Anti Ballistic Missiles';

            case 503:
                return 'Interplanetary Missiles';

            case 202:
                return 'Small Cargo';

            case 203:
                return 'Large Cargo';

            case 204:
                return 'Light Fighter';

            case 205:
                return 'Heavy Fighter';

            case 206:
                return 'Cruiser';

            case 207:
                return 'Battleship';

            case 208:
                return 'ColonyShip';

            case 209:
                return 'Recycler';

            case 210:
                return 'Espionage Probe';

            case 211:
                return 'Bomber';

            case 212:
                return 'Solar Satellite';

            case 213:
                return 'Destroyer';

            case 214:
                return 'Deathstar';

            case 215:
                return 'Battlecruiser';

            case 217:
                return 'Crawler';

            case 218:
                return 'Reaper';

            case 219:
                return 'Pathfinder';

            case 106:
                return 'Espionage Technology';

            case 108:
                return 'Computer Technology';

            case 109:
                return 'Weapons Technology';

            case 110:
                return 'Shielding Technology';

            case 111:
                return 'Armour Technology';

            case 113:
                return 'Energy Technology';

            case 114:
                return 'Hyperspace Technology';

            case 115:
                return 'Combustion Drive';

            case 117:
                return 'Impulse Drive';

            case 118:
                return 'Hyperspace Drive';

            case 120:
                return 'Laser Technology';

            case 121:
                return 'Ion Technology';

            case 122:
                return 'Plasma Technology';

            case 123:
                return 'Intergalactic ResearchNetwork';

            case 124:
                return 'Astrophysics';

            case 199:
                return 'Graviton Technology';

            default:
                return $typeId;
        }
    }

    public function getPlayerClass(int $classId): ?string
    {
        switch ($classId) {
            case Player::CLASS_NOT_SET:
                return 'no class';

            case Player::CLASS_COLLECTOR:
                return 'collector';

            case Player::CLASS_GENERAL:
                return 'general';

            case Player::CLASS_DISCOVER:
                return 'discover';
        }
    }
}
