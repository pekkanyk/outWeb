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
        //$form = $this->createForm(SearchType::class,new SearchProducts());
        $form = $this->createForm(SearchType::class,new SearchProducts(), array('csrf_protection' => false));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $products = $this->outletTuoteService->searchWith($form->getData());
            $active = $products['active'];
            $deleted = $products['deleted'];
            $aleprosentit = $this->outletTuoteService->aleprosentit($form->getData());
            $deleted_prosentit = $aleprosentit['deleted'];
            $active_prosentit = $aleprosentit['active'];
            
            return $this->render('index.html.twig',[
            'active'=> $active,
            'deleted'=> $deleted,
            'deleted_prosentit'=>$deleted_prosentit,
            'active_prosentit'=>$active_prosentit,
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
    
    /**
     *@Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        //$form = $this->createForm(SearchType::class,new SearchProducts());
        $form = $this->createForm(SearchType::class,new SearchProducts(), array('csrf_protection' => false));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $products = $this->outletTuoteService->searchWith($form->getData());
            $active = $products['active'];
            $deleted = $products['deleted'];
            $aleprosentit = $this->outletTuoteService->aleprosentit($form->getData());
            $deleted_prosentit = $aleprosentit['deleted'];
            $active_prosentit = $aleprosentit['active'];
            
            return $this->render('search.html.twig',[
            'active'=> $active,
            'deleted'=> $deleted,
            'deleted_prosentit'=>$deleted_prosentit,
            'active_prosentit'=>$active_prosentit,
            'form'=> $form->createView(),
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        }
        
        
        return $this->render('search.html.twig',[
            'active'=>null,
            'deleted'=>null,
            'form'=> $form->createView(),
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        
    }
    
    
    
}