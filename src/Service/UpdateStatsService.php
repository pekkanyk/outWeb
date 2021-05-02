<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\UpdateStats;
use Doctrine\ORM\EntityManagerInterface;


class UpdateStatsService{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function getStats(){
        $db = $this->entityManager->getRepository(UpdateStats::class);
        return  $db->findLastInserted();
    }
    
    public function getDayStats($alkaen, $asti){
        $db = $this->entityManager->getRepository(UpdateStats::class);
        return $db->getDayStats($alkaen,$asti);
    }
    
}
