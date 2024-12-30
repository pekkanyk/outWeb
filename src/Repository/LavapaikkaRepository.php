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
    
    public function getVali($kaytava, $vali)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.kaytava = :kaytava')
            ->andWhere('l.vali = :vali' )
            ->setParameter('kaytava', $kaytava)
            ->setParameter('vali', $vali)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function getTaso($kaytava, $taso)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.kaytava = :kaytava')
            ->andWhere('l.taso = :taso' )
            ->setParameter('kaytava', $kaytava)
            ->setParameter('taso', $taso)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function getOddTaso($kaytava, $taso)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.kaytava = :kaytava')
            ->andWhere('l.taso = :taso' )
            ->andWhere('mod(l.vali,2) != 0')
            ->setParameter('kaytava', $kaytava)
            ->setParameter('taso', $taso)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function getEvenTaso($kaytava, $taso)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.kaytava = :kaytava')
            ->andWhere('l.taso = :taso' )
            ->andWhere('mod(l.vali,2) = 0')
            ->setParameter('kaytava', $kaytava)
            ->setParameter('taso', $taso)
            ->orderBy('l.vali', 'DESC')
            ->addOrderBy('l.reuna', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function haeOutId($haku)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.sisalto LIKE :haku')
            ->setParameter('haku', $haku)
            ->getQuery()
            ->getResult()
        ;
    }

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
