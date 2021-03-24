<?php

namespace App\Controller;

use App\Form\SpyReportType;
use App\Handler\Spy\AddSpyReportHandler;
use App\OGame\DataTransformer\SpyReportDataTransformer;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $apiKey = $form->get('report')->getData();

            ($addSpyReportHandler)(
                $server,
                $apiKey
            );

            $this->addFlash('success', 'Spy report has been added!');

            return $this->redirectToRoute('app_spy');
        }

        $reports = $this->spyRepository->getOfServer($server);

        return $this->render('spy/list.html.twig', [
            'reports' => $reports,
            'form' => $form->createView(),
        ]);
    }
}
