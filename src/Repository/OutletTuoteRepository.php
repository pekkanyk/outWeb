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
    
    public function searchActive($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.priceUpdatedDate BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->andWhere('o.condition IN (:kl)')
                ->orderBy('o.'.$orderby,$direction)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('searchStr',$searchStr)
                ->setParameter('kl',$kl)
                ->getQuery()
                ->getResult();
    }
    
    public function searchDeleted($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->andWhere('o.condition IN (:kl)')
                ->orderBy('o.'.$orderby,$direction)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('searchStr',$searchStr)
                ->setParameter('kl',$kl)
                ->getQuery()
                ->getResult();
    }
    
    public function sumDeletedPrices($price,$alkaen,$asti,$minprice,$maxprice,$kl) {
        return $this->createQueryBuilder('o')
                ->select('SUM (o.'.$price.')')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('o.condition IN (:kl)')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('kl',$kl)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function sumActivePrices($price,$alkaen,$asti,$minprice,$maxprice,$kl) {
        return $this->createQueryBuilder('o')
                ->select('SUM (o.'.$price.')')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.priceUpdatedDate BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('o.condition IN (:kl)')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('kl',$kl)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    
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
    
    public function countActiveRows(){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NULL')
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDeletedRows(){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NOT NULL')
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDistinctDeletedRows(){
        return $this->createQueryBuilder('o')
                ->select('COUNT (DISTINCT o.pid)')
                ->andWhere('o.deleted IS NOT NULL')
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDistinctActiveRows(){
        return $this->createQueryBuilder('o')
                ->select('COUNT (DISTINCT o.pid)')
                ->andWhere('o.deleted IS NULL')
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function countActiveRowsCondition($condition){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.condition = :condition')
                ->setParameter('condition',$condition)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDeletedRowsCondition($condition){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.condition = :condition')
                ->setParameter('condition',$condition)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function top10DistinctActiveNumbers() {
        return $this->createQueryBuilder('o')
                ->select('(o.pid),COUNT(o.pid) AS CountOf')
                ->andWhere('o.deleted IS NULL')
                ->groupBy('o.pid')
                ->orderBy('CountOf', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
    }
    
    public function top10DistinctDeletedNumbers() {
        return $this->createQueryBuilder('o')
                ->select('(o.pid),COUNT(o.pid) AS CountOf')
                ->andWhere('o.deleted IS NOT NULL')
                ->groupBy('o.pid')
                ->orderBy('CountOf', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
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
