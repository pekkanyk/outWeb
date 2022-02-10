<?php

namespace App\Repository;

use App\Entity\PriceWatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceWatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceWatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceWatch[]    findAll()
 * @method PriceWatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceWatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceWatch::class);
    }

    public function getPricewatch($pid,$userId){
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :user')
            ->andWhere('b.pid = :pid')
            ->setParameter('user', $userId)
            ->setParameter('pid', $pid)    
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    public function getPidArray($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
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
    public function findOneBySomeField($value): ?PriceWatch
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
