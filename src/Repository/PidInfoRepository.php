<?php

namespace App\Repository;

use App\Entity\PidInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PidInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method PidInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method PidInfo[]    findAll()
 * @method PidInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PidInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PidInfo::class);
    }

    // /**
    //  * @return PidInfo[] Returns an array of PidInfo objects
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
    public function findOneBySomeField($value): ?PidInfo
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
