<?php

namespace App\Controller;

use App\Form\CoordinatesType;
use App\OGame\Helper;
use App\Repository\PlanetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartographyController extends AbstractController
{
    private $planetRepository;

    public function __construct(PlanetRepository $planetRepository)
    {
        $this->planetRepository = $planetRepository;
    }

    /**
     * @Route("/cartography", name="app_cartography")
     */
    public function galaxy(
        Request $request
    ): Response
    {
        $galaxy = Helper::checkGalaxyNumber($request->get('galaxy', 1));
        $system = Helper::checkSystemNumber($request->get('system', 1));

        $planetsInSystem = array_flip(range(1, 15));

        $planets = $this->planetRepository->getPlanetsOfSystem($galaxy, $system);
        foreach ($planets as $planet) {
            $planetsInSystem[$planet->getPosition()] = $planet;
        }

        $form = $this->container->get('form.factory')->createNamed('', CoordinatesType::class, [
            'galaxy' => $galaxy,
            'system' => $system,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galaxy = Helper::checkGalaxyNumber($form->getData()['galaxy']);
            $system = Helper::checkSystemNumber($form->getData()['system']);
        }

        return $this->render('cartography/galaxy.html.twig', [
            'galaxy' => $galaxy,
            'system' => $system,
            'planets' => $planetsInSystem,
            'form' => $form->createView(),
        ]);
    }
}
