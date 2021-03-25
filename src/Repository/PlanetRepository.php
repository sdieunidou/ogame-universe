<?php

namespace App\Repository;

use App\Entity\Alliance;
use App\Entity\Planet;
use App\Entity\Player;
use App\Entity\Server;
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
    public function getPlanetsOfSystem(Server $server, int $galaxy, int $system): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.server = :serverId')
            ->andWhere('p.galaxy = :galaxy')
            ->andWhere('p.system = :system')
            ->setParameter('galaxy', $galaxy)
            ->setParameter('system', $system)
            ->setParameter('serverId', $server->getId())
            ->orderBy('p.position', 'ASC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
        ;
    }

    public function deleteAllOfServerExcept(Server $server, array $ids)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.server = :serverId')
        ;

        if (!empty($ids)) {
            $qb
                ->andWhere(
                    $qb->expr()->notIn('p.ogameId', $ids)
                );
        }

        return $qb
            ->delete()
            ->setParameter('serverId', $server->getId())
            ->getQuery()
            ->execute()
        ;
    }

    public function deleteAllOfServer(Server $server)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.server = :serverId')
            ->delete()
            ->setParameter('serverId', $server->getId())
            ->getQuery()
            ->execute()
        ;
    }

    public function getGalaxiesOfPlayer(Server $server, Player $player): array
    {
        $rows = $this->createQueryBuilder('p')
            ->select('p.galaxy')
            ->innerJoin('p.player', 'pl')
            ->andWhere('p.player = :playerId')
            ->andWhere('p.server = :serverId')
            ->setParameter('playerId', $player->getId())
            ->setParameter('serverId', $server->getId())
            ->groupBy('p.galaxy')
            ->getQuery()
            ->getScalarResult()
        ;

        $galaxies = [];

        foreach ($rows as $row) {
            $galaxies[] = sprintf('G%d', $row['galaxy']);
        }

        return $galaxies;
    }

    public function getPlanetsOfAllianceOfGalaxy(Server $server, Alliance $alliance, int $galaxy): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.name, p.hasMoon, p.coordinates, p.system, p.galaxy, p.moonSize, pl.name as playerName, pl.id as playerId')
            ->innerJoin('p.player', 'pl')
            ->innerJoin('pl.alliance', 'a')
            ->andWhere('pl.alliance = :allianceId')
            ->andWhere('p.galaxy = :galaxy')
            ->andWhere('p.server = :serverId')
            ->setParameter('allianceId', $alliance->getId())
            ->setParameter('serverId', $server->getId())
            ->setParameter('galaxy', $galaxy)
            ->groupBy('p.system')
            ->orderBy('p.system')
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getPlanetOfCoordinates(Server $server, string $coordinates): ?Planet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.coordinates = :coords')
            ->andWhere('p.server = :serverId')
            ->setParameter('coords', $coordinates)
            ->setParameter('serverId', $server->getId())
            ->setMaxResults(1)
            ->groupBy('p.galaxy')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
