<?php

namespace App\Server;

use App\Entity\Server;
use App\Handler\Server\SwitchCurrentServerHandler;
use App\Repository\ServerRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CurrentServerResolver
{
    private $session;

    private $serverRepository;

    public function __construct(
        SessionInterface $session,
        ServerRepository $serverRepository
    ) {
        $this->session = $session;
        $this->serverRepository = $serverRepository;
    }

    public function getCurrentServer(): ?Server
    {
        if (!$this->session->has(SwitchCurrentServerHandler::CURRENT_SERVER_ID)) {
            throw new \LogicException('No server ID found in user session');
        }

        $serverId = $this->session->get(SwitchCurrentServerHandler::CURRENT_SERVER_ID);

        return $this->serverRepository->getServerOfId($serverId);
    }
}