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
        $pidStats->setActive_kaOutPrice($this->kaHinta($active));
        $pidStats->setDeleted_kaOutPrice($this->kaHinta($deleted));
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
    
    private function kaHinta($outletit) {
        $sum = 0;
        $count = count($outletit);
        if ($count>0){
            for ($i=0;$i<$count;$i++){
                $sum += $outletit[$i]->getOutPrice();
            }
            $ka = $sum / $count;
        }
        else{
            $ka = 0;
        }
        return $ka;
    }
}
