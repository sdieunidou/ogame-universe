<?php

namespace App\Controller;

use App\Entity\Server;
use App\Form\ServerType;
use App\Handler\Server\SwitchCurrentServerHandler;
use App\Handler\Server\UpdateServerDataHandler;
use App\OGame\API\ServerAPI;
use App\Repository\ServerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    private $serverRepository;

    private $switchCurrentServer;

    public function __construct(
        ServerRepository $serverRepository,
        SwitchCurrentServerHandler $switchCurrentServer
    ) {
        $this->serverRepository = $serverRepository;
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

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/servers", name="app_servers")
     */
    public function list(): Response {
        return $this->render('server/list.html.twig', [
            'servers' => $this->serverRepository->getServers(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/servers/add", name="app_server_add")
     */
    public function add(
        Request $request,
        UpdateServerDataHandler $updateServerDataHandler
    ): Response {
        $form = $this->createForm(ServerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $server = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($server);

            ($updateServerDataHandler)($server);

            return $this->redirectToRoute('app_server_edit', [
                'id' => $form->getData()->getId(),
            ]);
        }

        return $this->render('server/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/servers/{id}", name="app_server_edit")
     */
    public function edit(
        Server $server,
        Request $request,
        UpdateServerDataHandler $updateServerDataHandler
    ): Response {
        $form = $this->createForm(ServerType::class, $server);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            ($updateServerDataHandler)($server);

            $this->addFlash('success', sprintf('Server "%s" has been updated.', $server));

            return $this->redirectToRoute('app_server_edit', [
                'id' => $server->getId(),
            ]);
        }

        return $this->render('server/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
