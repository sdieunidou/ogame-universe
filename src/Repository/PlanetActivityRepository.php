<?php

namespace App\Repository;

use App\Entity\PlanetActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanetActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanetActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanetActivity[]    findAll()
 * @method PlanetActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanetActivity::class);
    }

    // /**
    //  * @return PlanetActivity[] Returns an array of PlanetActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlanetActivity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
