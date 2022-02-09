<?php

namespace App\Repository;

use App\Entity\Bookmarks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bookmarks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmarks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmarks[]    findAll()
 * @method Bookmarks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmarksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmarks::class);
    }

    
    public function getOutIdArray($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function getBookmark($outId,$userId){
        return $this->createQueryBuilder('b')
            ->andWhere('b.userId = :user')
            ->andWhere('b.outId = :outid')
            ->setParameter('user', $userId)
            ->setParameter('outid', $outId)    
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    /*
    public function getOutIdArray($userid)
    {
        return $this->createQueryBuilder('b')
                ->select('b.outId')
                ->andWhere('b.userId = :val')
                ->setParameter('val', $userid)
                ->orderBy('b.id','ASC')
                ->getQuery()
                ->getResult();
    }
*/
    

    /*
    public function findOneBySomeField($value): ?Bookmarks
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
