<?php

namespace App\Xtense;

use App\Entity\Server;
use App\Entity\User;
use App\Repository\ServerRepository;
use App\Repository\UserRepository;
use App\Xtense\Exception\XtenseException;

class Xtense
{
    const WARNING = 1;
    const ERROR = 2;
    const NORMAL = 3;
    const SUCCESS = 4;

    private $userRepository;

    private $serverRepository;

    public function __construct(
        UserRepository $userRepository,
        ServerRepository $serverRepository
    ) {
        $this->userRepository = $userRepository;
        $this->serverRepository = $serverRepository;
    }

    public function authenticate(string $password): User
    {
        $user = $this->userRepository->authenticateUserOfXtense($password);
        if (!$user instanceof User) {
            throw new XtenseException('Invalid user token');
        }

        return $user;
    }

    public function resolveServerOfUser(string $universe): Server
    {
        $tmp = explode('.', str_replace('https://', '', $universe));
        $tmp = explode('-', mb_substr($tmp[0], 1));

        if (empty($tmp[0]) || empty($tmp[1]) || !is_numeric($tmp[0])) {
            throw new XtenseException('Server not recognized');
        }

        $server = $this->serverRepository->getServerOfOgameId((int) $tmp[0], $tmp[1]);
        if (!$server instanceof Server) {
            throw new XtenseException('Server not recognized');
        }

        return $server;
    }

    public function getMicrotime()
    {
        $t = explode(' ', microtime());
        return ((float)$t[1] + (float)$t[0]);
    }

    public function processRequest($requestData, User $user, Server $server): array
    {
        $returnedData = [
            'status' => self::SUCCESS,
        ];

        $data = json_decode($requestData['data'], true);

        switch($requestData['type']) {
            case 'overview':
                break;

            case 'buildings':
                break;

            case 'resourceSettings':
                break;

            case 'defense':
                break;

            case 'researchs':
                break;

            case 'fleet':
                break;

            case 'system':
                break;

            case 'ranking':
                break;

            case 'rc':
            case 'rc_shared':
                break;

            case 'ally_list':
                break;

            case 'messages':
                break;

            case 'spy':
            case 'spy_shared':
                break;

            case 'ennemy_spy':
                break;

            case 'rc_cdr':
                break;

            case 'expedition':
            case 'expedition_shared':
                break;

            default:
                throw new XtenseException(sprintf('Type "%s" not managed', $requestData['type']));
        }

        return $returnedData;
    }
}