<?php

namespace App\Twig;

use App\Entity\Server;
use App\Handler\Server\SwitchCurrentServerHandler;
use App\Repository\ServerRepository;
use App\Server\CurrentServerResolver;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ServerExtension extends AbstractExtension
{
    private $serverResolver;

    private $session;

    private $serverRepository;

    public function __construct(
        CurrentServerResolver $serverResolver,
        SessionInterface $session,
        ServerRepository $serverRepository
    ) {
        $this->serverResolver = $serverResolver;
        $this->session = $session;
        $this->serverRepository = $serverRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_servers', [$this, 'getServers']),
            new TwigFunction('current_server', [$this, 'getCurrentServer']),
            new TwigFunction('current_server_id', [$this, 'getCurrentServerId']),
            new TwigFunction('current_server_name', [$this, 'getCurrentServerName']),
            new TwigFunction('current_server_language', [$this, 'getCurrentServerLanguage']),
        ];
    }

    public function getServers(): array
    {
        return $this->serverRepository->getServers();
    }

    public function getCurrentServer(): ?Server
    {
        return $this->serverResolver->getCurrentServer();
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
