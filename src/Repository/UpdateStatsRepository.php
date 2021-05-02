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
    
    public function findLastInserted()
    {
    return $this
        ->createQueryBuilder("o")
        ->orderBy("o.id", "DESC")
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }
     
    public function avgProdsFor($alkaen, $asti) {
        return $this->createQueryBuilder('o')
                ->select('AVG (o.totalItems)')
                ->andWhere('o.timestamp BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen->format('Y-m-d 00:00:01'))
                ->setParameter('asti',$asti->format('Y-m-d 23:59:59'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getDayStats($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->andWhere('o.timestamp BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen->format('Y-m-d 00:00:01'))
                ->setParameter('asti',$asti->format('Y-m-d 23:59:59'))
                ->orderBy("o.timestamp", "ASC")
                ->getQuery()
                ->getResult();
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
