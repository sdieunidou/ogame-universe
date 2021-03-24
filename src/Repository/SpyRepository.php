<?php

namespace App\Repository;

use App\Entity\Server;
use App\Entity\Spy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spy[]    findAll()
 * @method Spy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spy::class);
    }

    /**
     * @param Server $server
     * @param int|null $galaxy
     *
     * @return Spy[] Returns an array of Spy objects
     */
    public function getOfServer(Server $server, ?int $galaxy = null): array
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.server = :serverId')
            ->setParameter('serverId', $server->getId())
            ->orderBy('s.spyAt', 'DESC')
        ;

        if (null !== $galaxy) {
            $qb
                ->andWhere('s.galaxy = :galaxy')
                ->setParameter('galaxy', $galaxy)
            ;
        }

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Server $server
     * @param string $apiKey
     *
     * @return Spy|null
     */
    public function getOfApiKey(Server $server, string $apiKey): ?Spy
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.server = :serverId')
            ->andWhere('s.apiKey = :apiKey')
            ->setParameter('serverId', $server->getId())
            ->setParameter('apiKey', $apiKey)
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getLatestSpyReportOf(Server $server, string $coordinates, bool $isMoon = false): ?Spy
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.server = :serverId')
            ->andWhere('s.coordinates = :coords')
            ->andWhere('s.isMoon = :isMoon')
            ->setParameter('serverId', $server->getId())
            ->setParameter('coords', $coordinates)
            ->setParameter('isMoon', $isMoon)
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
