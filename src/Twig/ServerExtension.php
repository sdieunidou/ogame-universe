<?php

namespace App\Twig;

use App\Handler\Server\SwitchCurrentServerHandler;
use App\Repository\ServerRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ServerExtension extends AbstractExtension
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

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_server_id', [$this, 'getCurrentServerId']),
            new TwigFunction('current_server_name', [$this, 'getCurrentServerName']),
            new TwigFunction('current_server_language', [$this, 'getCurrentServerLanguage']),
        ];
    }

    public function getCurrentServerId(): ?int
    {
        return $this->session->get(SwitchCurrentServerHandler::CURRENT_SERVER_ID);
    }

    public function getCurrentServerName(): ?string
    {
        return $this->session->get(SwitchCurrentServerHandler::CURRENT_SERVER_NAME);
    }

    public function getCurrentServerLanguage(): ?string
    {
        return $this->session->get(SwitchCurrentServerHandler::CURRENT_SERVER_LANGUAGE);
    }
}
