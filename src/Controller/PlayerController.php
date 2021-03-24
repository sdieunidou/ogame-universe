<?php

namespace App\Controller;

use App\Entity\Player;
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
     * @Route("/players", name="app_players")
     */
    public function list(): Response
    {
        $players = $this->playerRepository->getPlayersActives(1000);

        return $this->render('player/list.html.twig', [
            'players' => $players,
        ]);
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
                'allowedScoreDiff' => 0,
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

    /**
     * @Route("/players/{id}", name="app_player_view")
     */
    public function view(Player $player): Response
    {
        return $this->render('player/view.html.twig', [
            'player' => $player,
        ]);
    }
}
