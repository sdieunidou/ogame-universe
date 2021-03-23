<?php

namespace App\Controller;

use App\Form\PlayersInactivesType;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    private $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    /**
     * @Route("/players/24h", name="app_players_inactives")
     */
    public function inactives24H(Request $request): Response
    {
        $form = $this->container->get('form.factory')->createNamed(
            '',
            PlayersInactivesType::class,
            [
                'minScore' => 500000,
                'minMilitaryScore' => 500000,
                'allowedScoreDiff' => 50000,
            ],
            [
                'method' => 'GET',
                'csrf_protection' => false,
            ]
        );

        $form->handleRequest($request);

        $players = $this->playerRepository->getInactives24H(
            $form->getData()['minScore'],
            $form->getData()['minMilitaryScore'],
            $form->getData()['allowedScoreDiff']
        );

        return $this->render('player/list_24h.html.twig', [
            'form' => $form->createView(),
            'players' => $players,
        ]);
    }
}
