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
        $startTime = $this->xtense->getMicrotime();

        $response = new Response();
        $requestData = json_decode($request->getContent(), true);

        try {
            if (empty($requestData['universe'])) {
                throw new XtenseException('"universe" not provided');
            }

            if (empty($requestData['password'])) {
                throw new XtenseException('"password" not provided');
            }

            if (empty($requestData['type'])) {
                throw new XtenseException('"type" not provided');
            }

            $user = $this->xtense->authenticate($requestData['password']);
            $server = $this->xtense->resolveServerOfUser($requestData['universe']);

            $data = $this->xtense->processRequest($requestData['type'], $user, $server);
            $data['execution'] = str_replace(',', '.', round(($this->xtense->getMicrotime() - $startTime) * 1000, 2));

            $response->setContent(json_encode($data));
        } catch (XtenseException $e) {
            $response->setContent('hack');
        }

        return $response;
    }
}
