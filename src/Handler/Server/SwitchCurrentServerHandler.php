<?php

namespace App\Handler\Server;

use App\Entity\Server;
use App\EventSubscriber\CurrentServerSubscriber;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SwitchCurrentServerHandler
{
    const CURRENT_SERVER_ID = 'server_id';
    const CURRENT_SERVER_NAME = 'server_name';
    const CURRENT_SERVER_LANGUAGE = 'server_language';

    private $session;

    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }

    public function __invoke(Server $server)
    {
        $this->session->set(self::CURRENT_SERVER_ID, $server->getId());
        $this->session->set(self::CURRENT_SERVER_NAME, $server->getName());
        $this->session->set(self::CURRENT_SERVER_LANGUAGE, $server->getLanguage());
    }
}
