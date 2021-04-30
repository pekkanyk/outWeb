<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OutletTuoteService;
use App\Service\UpdateStatsService;
use App\Form\Type\PidType;
use App\Form\Type\OutIdType;
use App\Model\SearchPid;
use App\Model\SearchOutId;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController{
    
    private OutletTuoteService $outletTuoteService;
    private UpdateStatsService $updateStatsService;
    
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
    }
    
    /**
     *@Route("/", name="homepage")
     */
    public function index(): Response
    {
        $today = (new \DateTime())->format('Y-m-d');
        return $this->render('index.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats(),
            'today'=>$today
            ]);
    } 
}
