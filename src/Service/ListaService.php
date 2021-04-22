<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\OutletTuote;
use Doctrine\ORM\EntityManagerInterface;


class ListaService{
    private $entityManager;
        
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
}
