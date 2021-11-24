<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ListaService;
use App\Service\OutletTuoteService;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ListaGenerate;
use App\Form\Type\ListaType;


class ListaController extends AbstractController{
    private ListaService $listaService;
    private OutletTuoteService $outletTuoteService;
    public function __construct(OutletTuoteService $outletTuoteService, ListaService $listaService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->listaService = $listaService;
    }
    /**
     *@Route("/listat")
     */
    public function listaForm(Request $request): Response {
        $form = $this->createForm(ListaType::class, new ListaGenerate());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            return $this->render('listaGenerate.html.twig',[
            'listarivit'=>$this->listaService->makeList($form->getData()->getString())
                ]);
            
            //return new Response(print_r($this->listaService->makeList($form->getData()->getString())));
        }
        
        return $this->render('listaForm.html.twig',[
            'form'=> $form->createView(),
                ]);
    }
    /**
     * @Route("/shitlist/")
     */
    public function shitLisDefault(): Response {
        return $this->redirect("/shitlist/7");
    }
    
    /**
     *@Route("/shitlist/{days}")
     */
    public function shitList(int $days, Request $request): Response {
        $days = intval($days);
        return $this->render('listaGenerate.html.twig',[
            'listarivit'=>$this->listaService->makeShitList($days)
                ]);
        
    }
    
    
   /*
    public function show(int $outId, Request $request): Response
    {
        $outId = intval($outId);
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
    }
    */
}
