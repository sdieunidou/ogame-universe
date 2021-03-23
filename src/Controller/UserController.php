<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/users", name="app_users")
     */
    public function list(
        UserRepository $userRepository
    ): Response {
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->getUsers(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @Route("/users/{id}", name="app_user_edit")
     */
    public function edit(
        User $user,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // update password if provided
            $newPassword = $form->get('newPassword')->getData();
            if (!empty($newPassword)) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf('User "%s" has been updated.', $user));

            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
