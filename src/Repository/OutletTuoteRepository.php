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
    
    public function setAllActiveDeleted(): void
    {
        $this->createQueryBuilder('o')
            ->update()
            ->set('o.deleted', ':today')
            ->where('o.deleted IS NULL')
            ->setParameter('today', date_create('now', new \DateTimeZone('Europe/Helsinki')))
            ->getQuery()
            ->execute()
        ;
    }
    
    public function getDates($alkaen, $asti) {
         return $this->createQueryBuilder('o')
                 ->select('DISTINCT o.deleted')
                 ->andWhere('o.deleted IS NOT NULL')
                 ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                 ->setParameter('alkaen',$alkaen->format('Y-m-d'))
                 ->setParameter('asti',$asti->format('Y-m-d'))
                 ->orderBy('o.deleted', 'DESC')
                 ->getQuery()
                 ->getResult();
    }
    
    public function countUpdatedOn($date) {
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.priceUpdatedDate = :pvm')
                ->andWhere('o.deleted IS NULL')
                ->setParameter('pvm',$date->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function countDeleted($date) {
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted = :pvm')
                ->setParameter('pvm',$date->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }

    public function countFirstSeen($date) {
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.firstSeen = :pvm')
                ->setParameter('pvm',$date->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countFirstSeenActive($date) {
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.firstSeen = :pvm')
                ->andWhere('o.deleted IS NULL')
                ->setParameter('pvm',$date->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }

    /*
    public function findByPid($pid, $status)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.pid = :val')
                ->setParameter('val', $pid)
            ->andWhere('o.deleted IS NULL')
                //->setParameter('status', $status)
            ->orderBy('o.outId', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
     * 
     */
    
    public function findByPidDeleted($pid)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.pid = :val')
            ->andWhere('o.deleted IS NOT NULL')
            ->setParameter('val', $pid)
            ->orderBy('o.deleted', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByFirstSeenDeleted($date)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.firstSeen = :val')
            ->andWhere('o.deleted IS NOT NULL')
            ->setParameter('val', $date)
            ->orderBy('o.deleted', 'DESC')
            ->getQuery()
            ->getResult()
        ;
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
