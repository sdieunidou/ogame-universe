<?php

namespace App\Handler\Xtense;

use App\Entity\User;
use App\Xtense\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;

final class TokenGeneratorHandler
{
    private $tokenGenerator;

    private $entityManager;

    public function __construct(
        TokenGenerator $tokenGenerator,
        EntityManagerInterface $entityManager
    )
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->entityManager = $entityManager;
    }

    public function __invoke(User $user)
    {
        $user->setXtensePassword($this->tokenGenerator->generate());

        $this->entityManager->flush();
    }
}