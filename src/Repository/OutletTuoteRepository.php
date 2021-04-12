<?php

namespace App\Repository;

use App\Entity\OutletTuote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OutletTuote|null find($id, $lockMode = null, $lockVersion = null)
 * @method OutletTuote|null findOneBy(array $criteria, array $orderBy = null)
 * @method OutletTuote[]    findAll()
 * @method OutletTuote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutletTuoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutletTuote::class);
    }

    // /**
    //  * @return OutletTuote[] Returns an array of OutletTuote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OutletTuote
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
