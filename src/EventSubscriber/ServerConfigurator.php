<?php

namespace App\EventSubscriber;

use App\Entity\Server;
use App\Handler\Server\SwitchCurrentServerHandler;
use App\Repository\ServerRepository;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ServerConfigurator implements EventSubscriberInterface
{
    private $session;

    private $serverRepository;

    private $entityManager;

    private $reader;

    public function __construct(
        SessionInterface $session,
        ServerRepository $serverRepository,
        EntityManagerInterface $entityManager,
        Reader $reader
    ) {
        $this->session = $session;
        $this->serverRepository = $serverRepository;
        $this->entityManager = $entityManager;
        $this->reader = $reader;
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
        if (!$this->session->has(SwitchCurrentServerHandler::CURRENT_SERVER_ID)) {
            throw new \LogicException('No server ID found in user session');
        }

        $serverId = $this->session->get(SwitchCurrentServerHandler::CURRENT_SERVER_ID);

        $server = $this->serverRepository->getServerOfId($serverId);
        if (!$server instanceof Server) {
            throw new \LogicException(sprintf('Server with ID "%d" not found', $serverId));
        }

        $filter = $this->entityManager->getFilters()->enable('server_filter');
        $filter->setParameter('id', $server->getId());
        $filter->setAnnotationReader($this->reader);
    }
}
