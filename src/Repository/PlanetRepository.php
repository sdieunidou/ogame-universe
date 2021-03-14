<?php

namespace App\Repository;

use App\Entity\Planet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Planet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planet[]    findAll()
 * @method Planet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planet::class);
    }

    /**
     * @param int $galaxy
     * @param int $system
     *
     * @return Planet[] Returns an array of Planet objects
     */
    public function getPlanetsOfSystem(int $galaxy, int $system): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.galaxy = :galaxy')
            ->andWhere('p.system = :system')
            ->setParameter('galaxy', $galaxy)
            ->setParameter('system', $system)
            ->orderBy('p.position', 'ASC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
        ;
    }
}
