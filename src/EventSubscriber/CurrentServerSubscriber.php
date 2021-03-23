<?php

namespace App\EventSubscriber;

use App\Entity\Server;
use App\Handler\Server\SwitchCurrentServerHandler;
use App\Repository\ServerRepository;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class CurrentServerSubscriber implements EventSubscriberInterface
{
    private $session;

    private $switchCurrentServer;

    public function __construct(
        SessionInterface $session,
        ServerRepository $serverRepository,
        SwitchCurrentServerHandler $switchCurrentServer
    ) {
        $this->session = $session;
        $this->serverRepository = $serverRepository;
        $this->switchCurrentServer = $switchCurrentServer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => [
                ['onKernelRequest', 0],
            ],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($this->session->has(SwitchCurrentServerHandler::CURRENT_SERVER_ID)
            && $this->session->has(SwitchCurrentServerHandler::CURRENT_SERVER_NAME)
            && $this->session->has(SwitchCurrentServerHandler::CURRENT_SERVER_LANGUAGE)
        ) {
            return;
        }

        $server = $this->serverRepository->getLatestOfLanguage(
            mb_substr($event->getRequest()->getPreferredLanguage(), 0, 2)
        );

        if ($server instanceof Server) {
            ($this->switchCurrentServer)($server);
            return;
        }

        $server = $this->serverRepository->getLatest();
        if ($server instanceof Server) {
            ($this->switchCurrentServer)($server);
            return;
        }
    }
}
