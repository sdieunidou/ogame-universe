<?php

namespace App\Controller;

use App\Form\GalaxyType;
use App\Form\SpyFilterType;
use App\Form\SpyReportType;
use App\Handler\Spy\AddSpyReportHandler;
use App\Repository\SpyRepository;
use App\Server\CurrentServerResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpyController extends AbstractController
{
    private $spyRepository;

    public function __construct(SpyRepository $spyRepository)
    {
        $this->spyRepository = $spyRepository;
    }

    /**
     * @Route("/spy", name="app_spy")
     */
    public function list(
        Request $request,
        CurrentServerResolver $serverResolver,
        AddSpyReportHandler $addSpyReportHandler
    ): Response
    {
        $server = $serverResolver->getCurrentServer();

        $form = $this->createForm(SpyReportType::class);
        $form->handleRequest($request);

        $formFilter = $this->container->get('form.factory')->createNamed(
            '',
            SpyFilterType::class,
            [
                'minDate' => new \DateTime('-10 days'),
                'minMilitaryScore' => 1000,
            ],
            [
                'method' => 'GET',
                'csrf_protection' => false,
                'server' => $server,
            ]
        );
        $formFilter->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            ($addSpyReportHandler)(
                $server,
                $form->get('report')->getData()
            );

            $this->addFlash('success', 'Spy report has been added!');

            return $this->redirectToRoute('app_spy');
        }

        $reports = $this->spyRepository->getOfServer(
            $server,
            $formFilter->get('galaxy')->getData(),
            $formFilter->get('minDate')->getData(),
            (int) ($formFilter->get('minMilitaryScore')->getData() * 1000000)
        );

        return $this->render('spy/list.html.twig', [
            'reports' => $reports,
            'form' => $form->createView(),
            'formFilter' => $formFilter->createView(),
        ]);
    }
}
