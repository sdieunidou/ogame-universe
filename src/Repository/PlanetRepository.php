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

    public function getGalaxiesOfPlayer(Player $player): array
    {
        $rows = $this->createQueryBuilder('p')
            ->select('p.galaxy')
            ->innerJoin('p.player', 'pl')
            ->andWhere('p.player = :playerId')
            ->setParameter('playerId', $player->getId())
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

    public function getPlanetsOfAllianceOfGalaxy(Alliance $alliance, int $galaxy): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.name, p.hasMoon, p.coordinates, p.system, p.galaxy, p.moonSize, pl.name as playerName')
            ->innerJoin('p.player', 'pl')
            ->innerJoin('pl.alliance', 'a')
            ->andWhere('pl.alliance = :allianceId')
            ->andWhere('p.galaxy = :galaxy')
            ->setParameter('allianceId', $alliance->getId())
            ->setParameter('galaxy', $galaxy)
            ->groupBy('p.system')
            ->orderBy('p.system')
            ->getQuery()
            ->getScalarResult()
        ;
    }
}
