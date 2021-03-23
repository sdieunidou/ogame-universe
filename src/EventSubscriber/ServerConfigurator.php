<?php

namespace App\EventSubscriber;

use App\Entity\Server;
use App\Server\CurrentServerResolver;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ServerConfigurator implements EventSubscriberInterface
{
    private $serverResolver;

    private $entityManager;

    private $reader;

    public function __construct(
        CurrentServerResolver $serverResolver,
        EntityManagerInterface $entityManager,
        Reader $reader
    ) {
        $this->serverResolver = $serverResolver;
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
        $server = $this->serverResolver->getCurrentServer();
        if (!$server instanceof Server) {
            throw new \LogicException(sprintf('No current server found in session'));
        }

        $filter = $this->entityManager->getFilters()->enable('server_filter');
        $filter->setParameter('id', $server->getId());
        $filter->setAnnotationReader($this->reader);
    }
}
