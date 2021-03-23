<?php

namespace App\Controller;

use App\Entity\Server;
use App\Handler\Server\SwitchCurrentServerHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    private $switchCurrentServer;

    public function __construct(
        SwitchCurrentServerHandler $switchCurrentServer
    ) {
        $this->switchCurrentServer = $switchCurrentServer;
    }

    /**
     * @Route("/server/switch/{id}", name="app_server_switch")
     */
    public function switch(Server $server): Response
    {
        ($this->switchCurrentServer)($server);

        return $this->redirectToRoute('app_home');
    }
}
