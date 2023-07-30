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
    public function countFirstSeenBetween($alku,$loppu){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.firstSeen BETWEEN :alku AND :loppu')
                ->setParameter('alku', $alku->format('Y-m-d'))
                ->setParameter('loppu', $loppu->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function countDeletedBetween($alku,$loppu){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted BETWEEN :alku AND :loppu')
                ->setParameter('alku', $alku->format('Y-m-d'))
                ->setParameter('loppu', $loppu->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function sumFirstSeenOutPriceBetween($alku,$loppu){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.outPrice)')
                ->andWhere('o.firstSeen BETWEEN :alku AND :loppu')
                ->setParameter('alku', $alku->format('Y-m-d'))
                ->setParameter('loppu', $loppu->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function sumDeletedOutPriceBetween($alku,$loppu){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.outPrice)')
                ->andWhere('o.deleted BETWEEN :alku AND :loppu')
                ->setParameter('alku', $alku->format('Y-m-d'))
                ->setParameter('loppu', $loppu->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function sumDeletedNorPriceBetween($alku,$loppu){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.norPrice)')
                ->andWhere('o.deleted BETWEEN :alku AND :loppu')
                ->setParameter('alku', $alku->format('Y-m-d'))
                ->setParameter('loppu', $loppu->format('Y-m-d'))
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getExamplePid($pid){
        return $this->createQueryBuilder('o')
            ->andWhere('o.pid = :pid')
            ->setParameter('pid', $pid)
            ->orderBy('o.firstSeen','DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    public function getCheapestActivePid($pid){
        return $this->createQueryBuilder('o')
            ->andWhere('o.pid = :pid')
            ->andWhere('o.deleted IS NULL')
            ->setParameter('pid', $pid)
            ->orderBy('o.outPrice','ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
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
    
    public function getRowsIn($outIds) {
        return $this->createQueryBuilder('o')
                ->andWhere('o.outId IN :ids')
                ->setParameter('ids',$outIds)
                ->getQuery()
                ->getResult();
    }
    
    public function noInfo(){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.info IS NULL')
                ->andWhere('o.condition LIKE :condition')
                ->setParameter('condition','C')
                ->orderBy('o.outId','DESC')
                ->getQuery()
                ->getResult();
    }
    
    public function poisto(){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.poistotuote != TRUE')
                ->orderBy('o.firstSeen','ASC')
                ->getQuery()
                ->getResult();
    }
    
    public function poisto_lastPrice($minimum){
        return $this->createQueryBuilder('o')
                ->andWhere('o.outPrice >= :minimum')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.poistotuote != TRUE')
                ->setParameter('minimum', $minimum)
                ->orderBy('o.priceUpdatedDate','ASC')
                ->getQuery()
                ->getResult();
    }
    
    public function dumppi(){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.poistotuote = TRUE')
                ->andWhere('o.dumppituote = TRUE')
                ->orderBy('o.firstSeen','ASC')
                ->getQuery()
                ->getResult();
    }
    
    public function getLongest_deleted($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('o.outId')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->orderBy('DATE_DIFF (o.deleted, o.firstSeen)','DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getLongest_active($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('o.outId')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->orderBy('DATE_DIFF (CURRENT_DATE(), o.firstSeen)','DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function getLongestTop10(){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->orderBy('DATE_DIFF (CURRENT_DATE(), o.firstSeen)','DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
    }
    public function getLongestTop1000($maxprice){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.outPrice <= :max')
                ->orderBy('DATE_DIFF (CURRENT_DATE(), o.firstSeen)','DESC')
                ->setParameter('max',$maxprice)
                ->setMaxResults(1000)
                ->getQuery()
                ->getResult();
    }
    public function activeSumOut($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.outPrice)')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function activeSumNor($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.norPrice)')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function deletedSumOut($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.outPrice)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function deletedSumNor($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.norPrice)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function activeAvgDays($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('AVG (DATE_DIFF (CURRENT_DATE(),o.firstSeen))')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function deletedAvgDays($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('AVG (DATE_DIFF (o.deleted, o.firstSeen))')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function activeAvgDaysUpdated($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('AVG (DATE_DIFF (CURRENT_DATE(),o.priceUpdatedDate))')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function deletedAvgDaysUpdated($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('AVG (DATE_DIFF (o.deleted, o.priceUpdatedDate))')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function activeDaySpread(){
        return $this->createQueryBuilder('o')
                ->select('COUNT(o.pid) AS CountOf, DATE_DIFF (CURRENT_DATE(),o.priceUpdatedDate) AS Days')
                ->andWhere('o.deleted IS NULL')
                ->groupBy('Days')
                ->orderBy('Days', 'DESC')
                ->getQuery()
                ->getResult();
    }
    
    public function activeDaySpreadFirstSeen(){
        return $this->createQueryBuilder('o')
                ->select('COUNT(o.pid) AS CountOf, DATE_DIFF (CURRENT_DATE(),o.firstSeen) AS Days')
                ->andWhere('o.deleted IS NULL')
                ->groupBy('Days')
                ->orderBy('Days', 'DESC')
                ->getQuery()
                ->getResult();
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
    
    public function searchActive($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl,$size,$act){
        if ($act == "Ei"){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.priceUpdatedDate BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->andWhere('o.poistotuote != TRUE')
                ->orderBy('o.'.$orderby,$direction)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('searchStr',$searchStr)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->getQuery()
                ->getResult();
    }
        else {
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.priceUpdatedDate BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->orderBy('o.'.$orderby,$direction)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('searchStr',$searchStr)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->getQuery()
                ->getResult();
        }
    }
    public function searchActiveWithNorPrice($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl,$nor_min,$nor_max){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.priceUpdatedDate BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('o.norPrice >= :nor_min')
                ->andWhere('o.norPrice <= :nor_max')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->andWhere('o.condition IN (:kl)')
                ->orderBy('o.'.$orderby,$direction)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('nor_min',$nor_min)
                ->setParameter('nor_max',$nor_max)
                ->setParameter('searchStr',$searchStr)
                ->setParameter('kl',$kl)
                ->getQuery()
                ->getResult();
    }
    
    public function searchDeleted($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl,$size,$act){
        if ($act == "Ei"){
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->andWhere('o.poistotuote != TRUE')
                ->orderBy('o.'.$orderby,$direction)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('searchStr',$searchStr)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->getQuery()
                ->getResult();
        }
        else {
        return $this->createQueryBuilder('o')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->orderBy('o.'.$orderby,$direction)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('searchStr',$searchStr)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->getQuery()
                ->getResult();
        }
    }
    
    public function sumDeletedPrices($price,$alkaen,$asti,$minprice,$maxprice,$kl,$searchStr,$size,$act) {
        if ($act=="Ei"){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.'.$price.')')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->andWhere('o.poistotuote != TRUE')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->setParameter('searchStr',$searchStr)
                ->getQuery()
                ->getSingleScalarResult();
        }
        else {
        return $this->createQueryBuilder('o')
                ->select('SUM (o.'.$price.')')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->setParameter('searchStr',$searchStr)
                ->getQuery()
                ->getSingleScalarResult();
        }
        
    }
    
    public function sumActivePrices($price,$alkaen,$asti,$minprice,$maxprice,$kl,$searchStr,$size,$act) {
        if ($act=="Ei"){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.'.$price.')')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.priceUpdatedDate BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->andWhere('o.poistotuote != TRUE')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->setParameter('searchStr',$searchStr)
                ->getQuery()
                ->getSingleScalarResult();
        }
        else {
        return $this->createQueryBuilder('o')
                ->select('SUM (o.'.$price.')')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.priceUpdatedDate BETWEEN :alkaen AND :asti')
                ->andWhere('o.outPrice >= :minprice')
                ->andWhere('o.outPrice <= :maxprice')
                ->andWhere('o.condition IN (:kl)')
                ->andWhere('o.koko IN (:size)')
                ->andWhere('UPPER(o.name) LIKE UPPER(:searchStr)')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('minprice',$minprice)
                ->setParameter('maxprice',$maxprice)
                ->setParameter('kl',$kl)
                ->setParameter('size',$size)
                ->setParameter('searchStr',$searchStr)
                ->getQuery()
                ->getSingleScalarResult();  
        }
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
    
    public function countActiveRows($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDeletedRows($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDistinctDeletedRows($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('COUNT (DISTINCT o.pid)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDistinctActiveRows($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('COUNT (DISTINCT o.pid)')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function countActiveRowsCondition($alkaen,$asti,$condition){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.condition = :condition')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('condition',$condition)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function countDeletedRowsCondition($alkaen,$asti,$condition){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.condition = :condition')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->setParameter('condition',$condition)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function top10DistinctActiveNumbers($alkaen,$asti) {
        return $this->createQueryBuilder('o')
                ->select('(o.pid),COUNT(o.pid) AS CountOf')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->groupBy('o.pid')
                ->orderBy('CountOf', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
    }
    
    public function top10DistinctDeletedNumbers($alkaen,$asti) {
        return $this->createQueryBuilder('o')
                ->select('(o.pid),COUNT(o.pid) AS CountOf')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->groupBy('o.pid')
                ->orderBy('CountOf', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
    }
    
    public function topDistinctActiveNumbers() {
        return $this->createQueryBuilder('o')
                ->select('(o.pid),COUNT(o.pid) AS CountOf')
                ->andWhere('o.deleted IS NULL')
                ->groupBy('o.pid')
                ->orderBy('CountOf', 'DESC')
                ->setMaxResults(100)
                ->getQuery()
                ->getResult();
    }
    
    public function hyllypaikkaInvis($vikat, $kerroin){
        return $this->createQueryBuilder('o')
                ->andWhere('mod((o.outId-:haku),:kerroin) = 0')
                ->andWhere('o.deleted IS NULL')
                ->addOrderBy('o.koko', 'ASC')
                ->addOrderBy('o.priceUpdatedDate','ASC')
                ->setParameter('haku',$vikat)
                ->setParameter('kerroin',$kerroin)
                ->getQuery()
                ->getResult();
    }
    
    public function hyllypaikkaInvisCount($vikat,$kerroin){
        return $this->createQueryBuilder('o')
                ->select('COUNT(o.outId)')
                ->andWhere('mod((o.outId-:haku),:kerroin) = 0')
                ->andWhere('o.deleted IS NULL')
                ->setParameter('haku',$vikat)
                ->setParameter('kerroin',$kerroin)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getOldest_deleted($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('o.outId')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->orderBy('o.outId','ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getOldest($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('o.outId')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->orderBy('o.outId','ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getNewest_deleted($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('o.outId')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->orderBy('o.outId','DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getNewest($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('o.outId')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->orderBy('o.outId','DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function getNewest_deletedPid($pid){
        return $this->createQueryBuilder('o')
                ->select('o')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.pid = :pid')
                ->orderBy('o.deleted','DESC')
                ->setParameter('pid',$pid)
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();
    }
    
    public function getAvgDeletedOutId($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('AVG (o.outId)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    public function getAvgActiveOutId($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('AVG (o.outId)')
                ->andWhere('o.deleted IS NULL')
                ->andWhere('o.firstSeen BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function countUnder12monthWarranty($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.warranty < 12')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function sumOutPriceUnder12monthWarranty($alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.outPrice)')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.warranty < 12')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }

    public function sumOutPriceBetweenNorPrice($minPrice,$maxPrice,$alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.outPrice)')
                ->andWhere('o.norPrice > :min AND o.norPrice <= :max')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('min',$minPrice)
                ->setParameter('max',$maxPrice)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function sumNorPriceBetweenNorPrice($minPrice,$maxPrice,$alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('SUM (o.norPrice)')
                ->andWhere('o.norPrice > :min AND o.norPrice <= :max')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('min',$minPrice)
                ->setParameter('max',$maxPrice)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
    public function countBetweenNorPrice($minPrice,$maxPrice,$alkaen,$asti){
        return $this->createQueryBuilder('o')
                ->select('COUNT (o)')
                ->andWhere('o.norPrice > :min AND o.norPrice <= :max')
                ->andWhere('o.deleted IS NOT NULL')
                ->andWhere('o.deleted BETWEEN :alkaen AND :asti')
                ->setParameter('min',$minPrice)
                ->setParameter('max',$maxPrice)
                ->setParameter('alkaen',$alkaen)
                ->setParameter('asti',$asti)
                ->getQuery()
                ->getSingleScalarResult();
    }
}
