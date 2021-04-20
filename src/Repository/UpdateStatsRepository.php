<?php

namespace App\Repository;

use App\Entity\UpdateStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UpdateStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method UpdateStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method UpdateStats[]    findAll()
 * @method UpdateStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpdateStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UpdateStats::class);
    }

    // /**
    //  * @return UpdateStats[] Returns an array of UpdateStats objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UpdateStats
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
