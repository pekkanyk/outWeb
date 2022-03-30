<?php

namespace App\Repository;

use App\Entity\Lavapaikka;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lavapaikka|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lavapaikka|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lavapaikka[]    findAll()
 * @method Lavapaikka[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LavapaikkaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lavapaikka::class);
    }

    // /**
    //  * @return Lavapaikka[] Returns an array of Lavapaikka objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lavapaikka
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
