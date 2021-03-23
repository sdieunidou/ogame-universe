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

    /**
     * @param int $minScore
     * @param int $minMilitaryScore
     * @param int $allowedScoreDiff
     *
     * @return Player[] Returns an array of Player objects
     */
    public function getInactives24H(
        int $minScore = 500000,
        int $minMilitaryScore = 500000,
        int $allowedScoreDiff = 50000
    ): array {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->andWhere('p.score >= :minScore')
            ->andWhere('p.militaryScore >= :minMilitaryScore')
            ->andWhere('p.scoreAt24H <= p.score')
            ->setParameter('minScore', $minScore)
            ->setParameter('minMilitaryScore', $minMilitaryScore)
            ->setParameter('allowedScoreDiff', $allowedScoreDiff)
            ->orderBy('p.score', 'DESC')
            ->having('p.score - p.scoreAt24H <= :allowedScoreDiff')
            ->getQuery()
            ->getResult()
        ;
    }
}
