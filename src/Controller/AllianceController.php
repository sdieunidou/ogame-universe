<?php

namespace App\Controller;

use App\Entity\Alliance;
use App\Repository\AllianceRepository;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllianceController extends AbstractController
{
    private $allianceRepository;

    public function __construct(AllianceRepository $allianceRepository)
    {
        $this->allianceRepository = $allianceRepository;
    }

    /**
     * @Route("/alliances", name="app_alliances")
     */
    public function list(): Response
    {
        $alliances = $this->allianceRepository->getAlliances();

        return $this->render('alliance/list.html.twig', [
            'alliances' => $alliances,
        ]);
    }

    /**
     * @Route("/alliance/{id}", name="app_alliance_view")
     */
    public function view(Alliance $alliance, PlayerRepository $playerRepository): Response
    {
        $players = $playerRepository->getPlayersOfAlliance($alliance);

        return $this->render('alliance/view.html.twig', [
            'alliance' => $alliance,
            'players' => $players,
        ]);
    }
}
