<?php

namespace App\Controller;

use App\Form\CoordinatesType;
use App\OGame\Helper;
use App\Repository\PlanetRepository;
use App\Server\CurrentServerResolver;
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
        Request $request,
        CurrentServerResolver $serverResolver
    ): Response {
        $server = $serverResolver->getCurrentServer();

        $form = $this->container->get('form.factory')->createNamed(
            '',
            CoordinatesType::class,
            [
                'galaxy' => $request->get('galaxy', 1),
                'system' => $request->get('system', 1),
            ],
            [
                'method' => 'GET',
                'csrf_protection' => false,
                'server' => $server,
            ]
        );

        $form->handleRequest($request);

        $planetsInSystem = array_flip(range(1, 15));

        $planets = $this->planetRepository->getPlanetsOfSystem(
            $server,
            $form->getData()['galaxy'],
            $form->getData()['system']
        );

        foreach ($planets as $planet) {
            $planetsInSystem[$planet->getPosition()] = $planet;
        }

        return $this->render('cartography/galaxy.html.twig', [
            'galaxy' => $form->getData()['galaxy'],
            'system' => $form->getData()['system'],
            'planets' => $planetsInSystem,
            'form' => $form->createView(),
        ]);
    }
}
