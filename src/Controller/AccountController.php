<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Handler\Xtense\TokenGeneratorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/my/account", name="app_account")
     */
    public function account(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // update password if provided
            $newPassword = $form->get('newPassword')->getData();

            if (!empty($newPassword)) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $newPassword
                    )
                );
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Your profile has been updated.');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/my/xtense", name="app_account_xtense")
     */
    public function xtense(TokenGeneratorHandler $tokenGeneratorHandler): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (empty($user->getXtensePassword())) {
            ($tokenGeneratorHandler)($user);
        }

        return $this->render('account/xtense.html.twig');
    }
}
