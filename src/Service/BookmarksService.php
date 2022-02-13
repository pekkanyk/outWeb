<?php

namespace App\Service;

use App\Entity\Bookmarks;
use App\Entity\PriceWatch;
use App\Entity\OutletTuote;
use App\Model\PriceWatchRow;
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
    
    public function priceWatchRows($userId){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $priceWatchRows = [];
        $priceWatchObjects = $this->getPriceWatches($userId);
        for ($i=0;$i<count($priceWatchObjects);$i++){
            $pid = $priceWatchObjects[$i]->getPid();
            $cheapestPid = $db->getCheapestActivePid($pid);
            $examplePid = $db->getExamplePid($pid);
            $name = $examplePid->getName();
            if ($cheapestPid != null){
                $halvin = $cheapestPid->getOutPrice();
            }
            else{
                $halvin = 0;
            }
            $limit = $priceWatchObjects[$i]->getPriceLimit();
            $armed = $priceWatchObjects[$i]->getArmed();
            $priceWatchRows[] = new PriceWatchRow($pid,$name,$halvin,$limit,$armed);
        }
        return $priceWatchRows;
    }
    
    public function getPriceWatches($userId){
        $db = $this->entityManager->getRepository(PriceWatch::class);
        //$bookmarksObjects = $db->findByUserId($userId);
       $priceWatchObjects = $db->getPidArray($userId);
        return $priceWatchObjects;
    }
    
    public function getAllPriceWatches(){
        $db = $this->entityManager->getRepository(PriceWatch::class);
        //$bookmarksObjects = $db->findByUserId($userId);
       $priceWatchObjects = $db->findAll();
        return $priceWatchObjects;
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
    
    public function isPricewatch($pid,$userId){
        $db = $this->entityManager->getRepository(PriceWatch::class);
        if ($db->getPricewatch($pid,$userId) != null) {
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
    
    public function addPid($pid,$userId){
        $db = $this->entityManager->getRepository(PriceWatch::class);
        if ($db->getPricewatch($pid,$userId) == null){
            $pricewatchObject = new PriceWatch();
            $pricewatchObject->setPid($pid);
            $pricewatchObject->setUserId($userId);
            $pricewatchObject->setPriceLimit(0);
            $pricewatchObject->setArmed(true);
            $this->entityManager->persist($pricewatchObject);
            $this->entityManager->flush();
        }
        
    }
    public function delPid($pid,$userId){
        $db = $this->entityManager->getRepository(PriceWatch::class);
        $pricewatchObject = $db->getPricewatch($pid,$userId);
        if ($pricewatchObject != null){
            $this->entityManager->remove($pricewatchObject);
            $this->entityManager->flush();
        }
    }
    
    public function editPid($pid,$userId,$limit,$armed){
        $int_limit = intval($limit);
        $db = $this->entityManager->getRepository(PriceWatch::class);
        $pricewatchObject = $db->getPricewatch($pid,$userId);
        if ($pricewatchObject != null){
            $pricewatchObject->setArmed($armed);
            $pricewatchObject->setPriceLimit($int_limit);
            $this->entityManager->persist($pricewatchObject);
            $this->entityManager->flush();
        }
    }
    
    public function unarmPriceWatch($pid,$userId){
        $db = $this->entityManager->getRepository(PriceWatch::class);
        $pricewatchObject = $db->getPricewatch($pid,$userId);
        $pricewatchObject->setArmed(false);
        $this->entityManager->persist($pricewatchObject);
        $this->entityManager->flush();
    }
    
}
