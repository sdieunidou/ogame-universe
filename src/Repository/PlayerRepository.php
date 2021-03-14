<?php

namespace App\Repository;

use App\Entity\Alliance;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @param Alliance $alliance
     *
     * @return Player[] Returns an array of Player objects
     */
    public function getPlayersOfAlliance(Alliance $alliance): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.alliance = :allianceId')
            ->setParameter('allianceId', $alliance->getId())
            ->orderBy('p.score', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
