<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\OutletTuote;
use App\Entity\PidInfo;
use App\Entity\UpdateStats;
use App\Model\PidStats;
use Doctrine\ORM\EntityManagerInterface;


class OutletTuoteService{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function makeDummy($outId){
        $today = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $outletTuote = new OutletTuote();
        $outletTuote->setPid(0);
        $outletTuote->setName("Not in database");
        $outletTuote->setOutId($outId);
        $outletTuote->setFirstSeen($today);
        $outletTuote->setPriceUpdatedDate($today);
        
        return $outletTuote;
    }
    
    public function searchWith($formData){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $result = [];
        $activity = $formData->getActivity();
        $alkaen = $this->makeDate($formData->getAlkaen(), "2020-01-01");
        $asti = $this->makeDate($formData->getAsti(), (new \DateTime("now"))->format('Y-m-d'));
        $minprice = intval($formData->getMinprice());
        $maxprice = $this->makeMaxPrice($formData->getMaxprice());
        $orderby = $formData->getOrderBy();
        $direction = $formData->getDirection();
        $searchStr = "%".$formData->getSearchStr()."%";
        if ($activity == "both"){
            $orderby= $this->makeOrderBy($orderby, 'active');
            $result['active'] = $db->searchActive($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr);
            $orderby= $this->makeOrderBy($orderby, 'deleted');
            $result['deleted'] = $db->searchDeleted($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr);
        }
        elseif ($activity=="active"){
            $orderby= $this->makeOrderBy($orderby, 'active');
            $result['active'] = $db->searchActive($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr);
            $result['deleted'] = null;
        }
        else{
            $result['active'] = null;
            $orderby= $this->makeOrderBy($orderby, 'deleted');
            $result['deleted'] = $db->searchDeleted($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr);
        }
        return $result;
    }
    
    private function validateDate($date, $format = 'Y-m-d'){
    $d = \DateTime::createFromFormat($format, $date);
    //return $d && $d->format($format) === $date;
    if ($d && $d->format($format) === $date){ return $d;}
    else {return null;}
    }
    
    private function makeDate($date,$default) {
        $dtz = new \DateTimeZone('Europe/Helsinki');
        //$date = $this->validateDate($date);
        if ($date!=null) { return $date; }
        else { return new \DateTime($default,$dtz); }
        
    }
    
    private function makeMaxPrice($price) {
        $price = intval($price);
        if ($price==0){return PHP_INT_MAX;}
        else {return $price;}
    }
    
    private function makeOrderBy($orderBy,$activity) {
        if ($orderBy == 'hakupvm' && $activity=='active'){
            return 'priceUpdatedDate';
        }
        elseif($orderBy == 'hakupvm' && $activity=='deleted'){
            return 'deleted';
        }
        else return $orderBy;
    }
    
    public function getAllWithPid($pid){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        return  $db->findBy(array('pid'=>$pid));
    }
    
    public function getOutletTuote($outId): ?OutletTuote{
        $db = $this->entityManager->getRepository(OutletTuote::class);
        return $db->find($outId);
    }


    public function getActiveWithPid($pid){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        return  $db->findBy(array('pid'=>$pid,'deleted'=>null));
    }
    
    public function getDeletedWithPid($pid){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        return $db->findByPidDeleted($pid);
    }
    
    public function getActiveFirstSeen(\DateTime $date) {
        $db = $this->entityManager->getRepository(OutletTuote::class);
        return $db->findBy(array('firstSeen'=>$date, 'deleted'=>null));
    }
    public function getDeletedFirstSeen(\DateTime $date){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        return $db->findByFirstSeenDeleted($date);
    }


    public function getPidInfo($pid){
        $pidDb = $this->entityManager->getRepository(PidInfo::class);
        return $pidDb->find($pid);
    }
    
    public function getPidStats($pid) {
        $exampleOutTuote = $this->getAllWithPid($pid);
        $pidStats = new PidStats();
        if ($exampleOutTuote!=null){
            $active = $this->getActiveWithPid($pid);
            $deleted = $this->getDeletedWithPid($pid);
            $pidStats->setName($exampleOutTuote[0]->getName());
            $pidStats->setActive_kaOutPrice($this->keskiarvo($active,"outPrice"));
            $pidStats->setDeleted_kaOutPrice($this->keskiarvo($deleted,"outPrice"));
            $pidStats->setActive_kaAlennus($this->keskiarvo($active,"alennus"));
            $pidStats->setDeleted_kaAlennus($this->keskiarvo($deleted,"alennus"));
            $pidStats->setActive_kaAlennusProsentti($this->keskiarvo($active,"alePros"));
            $pidStats->setDeleted_kaAlennusProsentti($this->keskiarvo($deleted,"alePros"));
            $pidStats->setActive_kaActiveDays($this->keskiarvo($active,"days"));
            $pidStats->setDeleted_kaActiveDays($this->keskiarvo($deleted,"days"));
            $pidStats->setPid($pid);
            $pidStats->setPidSize($this->getPidInfo($pid)->sizeStr());
            $pidStats->setPidCreated($exampleOutTuote[0]->getPidLuotu());
        }
        
        return $pidStats;
    }
        
    private function keskiarvo($outletit,$type) {
        $sum = 0;
        $sum2 = 0;
        $count = count($outletit);
        if ($count>0){
            for ($i=0;$i<$count;$i++){
                if ($type=="outPrice") { $sum += $outletit[$i]->getOutPrice(); }
                elseif ($type=="alennus"){ $sum += ($outletit[$i]->getNorPrice() - $outletit[$i]->getOutPrice()); }
                //elseif ($type=="alePros"){ $sum += $outletit[$i]->getAlennus(); }
                elseif ($type=="days"){ $sum += $outletit[$i]->daysActive();  }
                elseif ($type=="alePros"){
                    $sum += $outletit[$i]->getOutPrice();
                    $sum2 += $outletit[$i]->getNorPrice();
                }
            }
            if ($type=="alePros"){
                $ka = 100* (1-($sum/$sum2));
            }
            else{
                $ka = $sum / $count;
            }
            
        }
        else{
            $ka = 0;
        }
        return $ka;
    }
    
    public function getDates($alkaen, $asti){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        return $db->getDates($alkaen,$asti);
    }
    
    public function getNumbersFor($date){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $statsDb = $this->entityManager->getRepository(UpdateStats::class);
        $updated = $db->countUpdatedOn($date);
        $deleted = $db->countDeleted($date);
        $firstSeen = $db->countFirstSeen($date);
        $firstSeenActive = $db->countFirstSeenActive($date);
        $avgCount = $statsDb->avgProdsFor($date,$date);
        
        return ['updated'=>$updated,
                'deletedCount'=>$deleted,
                'firstCount'=>$firstSeen,
                'avgCount'=>$avgCount,
                'firstSeenActive'=>$firstSeenActive];
    }
}
