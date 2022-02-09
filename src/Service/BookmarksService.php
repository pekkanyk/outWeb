<?php

namespace App\Service;

use App\Entity\Bookmarks;
use Doctrine\ORM\EntityManagerInterface;


class BookmarksService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function getBookmarks($userId){
        $db = $this->entityManager->getRepository(Bookmarks::class);
        //$bookmarksObjects = $db->findByUserId($userId);
       $bookmarksObjects = $db->getOutIdArray($userId);
       $outIds = [];
        for ($i=0;$i<count($bookmarksObjects);$i++){
            $outIds[] = $bookmarksObjects[$i]->getOutId();
            //$outIds[] = 1;
        }
        return $outIds;
    }
    public function isBookmarked($outId,$userId){
        $db = $this->entityManager->getRepository(Bookmarks::class);
        if ($db->getBookmark($outId,$userId) != null) {
            return true;
        }
        else {
            return false;
        }
        
    }
    
    public function add($outId,$userId){
        $db = $this->entityManager->getRepository(Bookmarks::class);
        if ($db->getBookmark($outId,$userId) == null){
            $bookmarksObject = new Bookmarks();
            $bookmarksObject->setOutId($outId);
            $bookmarksObject->setUserId($userId);
            $this->entityManager->persist($bookmarksObject);
            $this->entityManager->flush();
        }
        
    }
    
    public function del($outId,$userId){
        $db = $this->entityManager->getRepository(Bookmarks::class);
        $bookmarksObject = $db->getBookmark($outId,$userId);
        if ($bookmarksObject != null){
            $this->entityManager->remove($bookmarksObject);
            $this->entityManager->flush();
        }
    }
    
}
