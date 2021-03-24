<?php

namespace App\Controller;

use App\Xtense\Exception\XtenseException;
use App\Xtense\Xtense;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class XtenseController extends AbstractController
{
    private $xtense;

    public function __construct(Xtense $xtense)
    {
        $this->xtense = $xtense;
    }

    /**
     * @Route("/mod/xtense/xtense.php", name="app_xtense_endpoint")
     */
    public function endpoint(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET');
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        $startTime = $this->xtense->getMicrotime();

        try {
            $user = $this->xtense->authenticate($request);
            $server = $this->xtense->resolveServerOfUser($request);

            $data = $this->xtense->processRequest($request, $user, $server);
            $data['execution'] = str_replace(',', '.', round(($this->xtense->getMicrotime() - $startTime) * 1000, 2));

            $response->setContent(json_encode($data));
        } catch (XtenseException $e) {
            $response->setContent('hack');
        }

        return $response;
    }
}
