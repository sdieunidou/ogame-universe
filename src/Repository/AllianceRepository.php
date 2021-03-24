<?php

namespace App\Repository;

use App\Entity\Alliance;
use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alliance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alliance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alliance[]    findAll()
 * @method Alliance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllianceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alliance::class);
    }

    /**
     * @return Alliance[] Returns an array of Alliance objects
     */
    public function getAlliances(Server $server): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.server = :serverId')
            ->setParameter('serverId', $server->getId())
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
