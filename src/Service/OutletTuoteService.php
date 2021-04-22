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
use Doctrine\ORM\EntityManagerInterface;


class OutletTuoteService{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function getOutIdInfo($outId){
        //$outIds = [["current"],["active"],["deleted"]];
        $outIds = [];
        $db = $this->entityManager->getRepository(OutletTuote::class);
        $pidDb = $this->entityManager->getRepository(PidInfo::class);
        $outletTuote = $db->find($outId);
        if ($outletTuote!=null){
            array_push($outIds,$outletTuote);
            array_push($outIds,$db->findBy(array('pid'=>$outletTuote->getPid(),'deleted'=>null)));
            array_push($outIds,$db->findByPidDeleted($outletTuote->getPid()));
            array_push($outIds,$pidDb->find($outletTuote->getPid()));
        }
        
        return $outIds;
    }
    
}
