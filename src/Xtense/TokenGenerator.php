<?php

namespace App\Xtense;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TokenGenerator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate(): string
    {
        $token = $this->getRandomToken();

        while (!$this->isUniqueToken($token)) {
            $token = $this->getRandomToken();
        }

        return $token;
    }

    private function isUniqueToken(string $token): bool
    {
        return !$this->entityManager->getRepository(User::class)->findOneByXtensePassword($token) instanceof User;
    }

    private function getRandomToken(): string
    {
        $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUYWXYZ';
        $token = '';

        for ($i = 0; $i < 40; ++$i) {
            $token .= mb_substr($string, mt_rand(0, 62), 1);
        }

        return $token;
    }
}
