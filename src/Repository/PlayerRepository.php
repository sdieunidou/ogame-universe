<?php

namespace App\Repository;

use App\Entity\Alliance;
use App\Entity\Player;
use App\Entity\Server;
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
     * @return Player[] Returns an array of Player objects
     */
    public function getPlayersActives(
        int $maxResults = 200,
        int $minScore = 500000,
        int $minMilitaryScore = 500000
    ): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.score > :minScore')
            ->andWhere('p.militaryScore > :minMilitaryScore')
            ->andWhere('p.status != :statusNoob')
            ->setParameter('minScore', $minScore)
            ->setParameter('minMilitaryScore', $minMilitaryScore)
            ->setParameter('statusNoob', Player::STATUS_HONORABLE);

        $qb
            ->andWhere(
                $qb->expr()->notIn('p.status', [
                    Player::STATUS_BANNED,
                    Player::STATUS_BANNED_INACTIVE,
                    Player::STATUS_BANNED_LONG_INACTIVE,
                    Player::STATUS_VACATION,
                    Player::STATUS_VACATION_INACTIVE,
                    Player::STATUS_VACATION_LONG_INACTIVE,
                ])
            )
        ;

        return $qb
            ->orderBy('p.score', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Player[] Returns an array of Player objects
     */
    public function getPlayers(int $maxResults = 200): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.score > :minScore')
            ->setParameter('minScore', 0)
            ->orderBy('p.score', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
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
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->andWhere('p.score >= :minScore')
            ->andWhere('p.militaryScore >= :minMilitaryScore')
            ->andWhere('p.scoreAt24H <= p.score')
            ->setParameter('minScore', $minScore)
            ->setParameter('minMilitaryScore', $minMilitaryScore)
            ->setParameter('allowedScoreDiff', $allowedScoreDiff)
            ->orderBy('p.score', 'DESC')
            ->having('p.score - p.scoreAt24H <= :allowedScoreDiff');

        $qb
            ->andWhere(
                $qb->expr()->notIn('p.status', [
                    Player::STATUS_BANNED,
                    Player::STATUS_BANNED_INACTIVE,
                    Player::STATUS_BANNED_LONG_INACTIVE,
                    Player::STATUS_INACTIVE,
                    Player::STATUS_LONG_INACTIVE,
                    Player::STATUS_VACATION,
                    Player::STATUS_VACATION_INACTIVE,
                    Player::STATUS_VACATION_LONG_INACTIVE,
                ])
            )
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPlayerOfId(Server $server, int $id): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.server = :serverId')
            ->andWhere('p.ogameId = :playerId')
            ->setParameter('serverId', $server->getId())
            ->setParameter('playerId', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
