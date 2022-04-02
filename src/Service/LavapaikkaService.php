<?php

namespace App\Service;

use App\Entity\Lavapaikka;
use Doctrine\ORM\EntityManagerInterface;


class LavapaikkaService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    public function add($lavapaikka){
        $db = $this->entityManager->getRepository(Lavapaikka::class);
        $this->entityManager->persist($lavapaikka);
        $this->entityManager->flush();
        
    }
    
    public function getCol($kaytava,$vali,$reuna){
        $db = $this->entityManager->getRepository(Lavapaikka::class);
        $lavapaikat = $db->getVali($kaytava,$vali);
        return $lavapaikat;
    }
    
    public function getTaso($kaytava,$taso){
        $db = $this->entityManager->getRepository(Lavapaikka::class);
        $lavapaikat = $db->getTaso($kaytava,$taso);
        return $lavapaikat;
    }
    public function getOddTaso($kaytava,$taso){
        $db = $this->entityManager->getRepository(Lavapaikka::class);
        $lavapaikat = $db->getOddTaso($kaytava,$taso);
        return $lavapaikat;
    }
    public function getEvenTaso($kaytava,$taso){
        $db = $this->entityManager->getRepository(Lavapaikka::class);
        $lavapaikat = $db->getEvenTaso($kaytava,$taso);
        return $lavapaikat;
    }
    public function editUsage($lp,$usage,$sisalto){
        $db = $this->entityManager->getRepository(Lavapaikka::class);
        $date = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $lavapaikka = $db->find($lp);
        if($lavapaikka!=null){
            $lavapaikka->setUsage($usage);
            $lavapaikka->setUpdated($date);
            $lavapaikka->setSisalto($sisalto);
            $this->entityManager->persist($lavapaikka);
            $this->entityManager->flush();
        }
        
    }
    public function enableOrDisable($lp){
        $db = $this->entityManager->getRepository(Lavapaikka::class);
        $lavapaikka = $db->find($lp);
        if ($lavapaikka != null){
            if ($lavapaikka->getUsable()){
                $lavapaikka->setUsable(false);
                $lavapaikka->setUsage(-1);
            }
            else {
                $lavapaikka->setUsable(true);
                $lavapaikka->setUsage(0);
            }
            $this->entityManager->persist($lavapaikka);
            $this->entityManager->flush();
        }
    }
}
