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
use App\Form\Type\SearchType;
use App\Model\SearchProducts;
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
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class,new SearchProducts());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $active = $this->outletTuoteService->searchWith($form->getData())['active'];
            $deleted = $this->outletTuoteService->searchWith($form->getData())['deleted'];
            
            return $this->render('index.html.twig',[
            'active'=> $active,
            'deleted'=> $deleted,
            'form'=> $form->createView(),
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        }
        
        return $this->render('index.html.twig',[
            'active'=>null,
            'deleted'=>null,
            'form'=> $form->createView(),
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
    }
    
}
/*
 * 
 * $outId = intval($outId);
	$today = (new \DateTime())->format('Y-m-d');
        $outletTuote = $this->outletTuoteService->getOutletTuote($outId);
        if ($outletTuote==null){ $outletTuote = $this->outletTuoteService->makeDummy($outId);}
        $active = $this->outletTuoteService->getActiveWithPid($outletTuote->getPid());
        $deleted = $this->outletTuoteService->getDeletedWithPid($outletTuote->getPid());
        $form = $this->createForm(OutIdType::class,new SearchOutId());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $this->redirect("/outid/".$form->getData()->getOutId());
        }
        return $this->render('outlet_tuote.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats(),
            'form'=> $form->createView(),
            'today'=>$today,
            'outletTuote'=> $outletTuote,
            'active'=>$active,
            'deleted'=>$deleted,
            'activeLkm'=>count($active),
            'deletedLkm'=>count($deleted),
            'pidInfo'=> $this->outletTuoteService->getPidInfo($outletTuote->getPid())]
                );
 */