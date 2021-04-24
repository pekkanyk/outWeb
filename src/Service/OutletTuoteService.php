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
use App\Model\PidStats;
use Doctrine\ORM\EntityManagerInterface;


class OutletTuoteService{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
    
    public function getPidInfo($pid){
        $pidDb = $this->entityManager->getRepository(PidInfo::class);
        return $pidDb->find($pid);
    }
    
    public function getPidStats($pid) {
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $pidStats = new PidStats();
        $active = $this->getActiveWithPid($pid);
        $deleted = $this->getDeletedWithPid($pid);
        $pidStats->setName($this->getPidName($active, $deleted));
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
        return $pidStats;
    }
    
    private function getPidName($active,$deleted) {
        if ($active != null){
            $name = $active[0]->getName();
        }
        else{
            $name = $deleted[0]->getName();
        }
        return $name;
    }
    
    private function keskiarvo($outletit,$type) {
        $sum = 0;
        $count = count($outletit);
        if ($count>0){
            for ($i=0;$i<$count;$i++){
                if ($type=="outPrice") { $sum += $outletit[$i]->getOutPrice(); }
                elseif ($type=="alennus"){ $sum += ($outletit[$i]->getNorPrice() - $outletit[$i]->getOutPrice()); }
                elseif ($type=="alePros"){ $sum += $outletit[$i]->getAlennus(); }
                elseif ($type=="days"){ $sum += $outletit[$i]->daysWithLastPrice(); }
            }
            $ka = $sum / $count;
        }
        else{
            $ka = 0;
        }
        return $ka;
    }
    /*
    private function kaAlennus($outletit) {
        $sum =0;
        $count = count($outletit);
        if ($count>0){
            for ($i=0;$i<$count;$i++){
                
            }
        }
    }
     * 
     */
}
