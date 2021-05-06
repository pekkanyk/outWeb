<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\OutletTuote;
use App\Entity\PidInfo;
use App\Entity\UpdateStats;
use App\Model\PidStats;
use App\Model\DbStats;
use App\Model\Top10Row;
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
    public function dbStats() {
        $dbstats = new DbStats();
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $dbstats->setActive_count($db->countActiveRows());
        $dbstats->setDeleted_count($db->countDeletedRows());
        $dbstats->setDeleted_uniikit($db->countDistinctDeletedRows());
        $dbstats->setActive_uniikit($db->countDistinctActiveRows());
        $dbstats->setActive_top10($this->getTop10($db->top10DistinctActiveNumbers()));
        $dbstats->setDeleted_top10($this->getTop10($db->top10DistinctDeletedNumbers()));
        $dbstats->setActive_count_A($db->countActiveRowsCondition('A'));
        $dbstats->setActive_count_B($db->countActiveRowsCondition('B'));
        $dbstats->setActive_count_C($db->countActiveRowsCondition('C'));
        $dbstats->setActive_count_D($db->countActiveRowsCondition('D'));
        $dbstats->setDeleted_count_A($db->countDeletedRowsCondition('A'));
        $dbstats->setDeleted_count_B($db->countDeletedRowsCondition('B'));
        $dbstats->setDeleted_count_C($db->countDeletedRowsCondition('C'));
        $dbstats->setDeleted_count_D($db->countDeletedRowsCondition('D'));
        return $dbstats;
    }
    
    private function getTop10($numbers){
        $top10rows = [];
        $db = $this->entityManager->getRepository(OutletTuote::class);
        for ($i=0;$i<count($numbers);$i++){
            $pid = $numbers[$i][1];
            $count = $numbers[$i]['CountOf'];
            $example = $db->findOneBy(['pid'=>$pid]);
            $name = $example->getName();
            
            array_push($top10rows,new Top10Row($pid, $name, $count)); 
        }
        return $top10rows;
    }
    
    public function aleprosentit($formData){
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $result = [];
        $activity = $formData->getActivity();
        $alkaen = $this->makeDate($formData->getAlkaen(), "2020-01-01");
        $asti = $this->makeDate($formData->getAsti(), (new \DateTime("now"))->format('Y-m-d'));
        $minprice = intval($formData->getMinprice());
        $maxprice = $this->makeMaxPrice($formData->getMaxprice());
        $kl = $this->makeKl($formData->getKl());
        if ($activity == "both"){
            $act_outPrices = $db->sumActivePrices('outPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $act_norPrices = $db->sumActivePrices('norPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $result['active'] = $this->countAle($act_outPrices, $act_norPrices);
            $del_outPrices = $db->sumDeletedPrices('outPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $del_norPrices = $db->sumDeletedPrices('norPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $result['deleted'] = $this->countAle($del_outPrices, $del_norPrices);
        }
        elseif ($activity=="active"){
            $act_outPrices = $db->sumActivePrices('outPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $act_norPrices = $db->sumActivePrices('norPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $result['active'] = $this->countAle($act_outPrices, $act_norPrices);
            $result['deleted'] = null;
        }
        else{
            $result['active'] = null;
            $del_outPrices = $db->sumDeletedPrices('outPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $del_norPrices = $db->sumDeletedPrices('norPrice',$alkaen,$asti,$minprice,$maxprice,$kl);
            $result['deleted'] = $this->countAle($del_outPrices, $del_norPrices);
        }
        return $result;
    }
    private function countAle($outPrices,$norPrices){
        if ($norPrices==0) return 0;
        else{
            return 100*(1-($outPrices/$norPrices));
        }
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
        $kl = $this->makeKl($formData->getKl());
        if ($activity == "both"){
            $orderby= $this->makeOrderBy($orderby, 'active');
            $result['active'] = $db->searchActive($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl);
            $orderby= $this->makeOrderBy($orderby, 'deleted');
            $result['deleted'] = $db->searchDeleted($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl);
        }
        elseif ($activity=="active"){
            $orderby= $this->makeOrderBy($orderby, 'active');
            $result['active'] = $db->searchActive($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl);
            $result['deleted'] = null;
        }
        else{
            $result['active'] = null;
            $orderby= $this->makeOrderBy($orderby, 'deleted');
            $result['deleted'] = $db->searchDeleted($alkaen,$asti,$minprice,$maxprice,$orderby,$direction,$searchStr,$kl);
        }
        return $result;
    }
    private function makeKl($kl) {
        if ($kl=='ANY'){return ['A','B','C','D'];}
        else { return [$kl];}
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
            $pidInfo=$this->getPidInfo($pid);
            if ($pidInfo!=null) { $pidStats->setPidSize($this->getPidInfo($pid)->sizeStr()); }
            else {$pidStats->setPidSize("- x - x -");}
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
