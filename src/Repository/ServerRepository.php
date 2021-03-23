<?php

namespace App\Repository;

use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    public function getLatestOfLanguage(string $language): ?Server
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.language = :language')
            ->setParameter('language', $language)
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getLatest(): ?Server
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
