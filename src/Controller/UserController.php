<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_users")
     */
    public function index(
        UserRepository $userRepository
    ): Response {
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
