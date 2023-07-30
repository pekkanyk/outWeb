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
     *@Route("/listat_no_mh")
     */
    public function listaForm2(Request $request): Response {
        $form = $this->createForm(ListaType::class, new ListaGenerate());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            return $this->render('listaGenerate.html.twig',[
            'listarivit'=>$this->listaService->makeList2($form->getData()->getString())
                ]);
            
            //return new Response(print_r($this->listaService->makeList($form->getData()->getString())));
        }
        
        return $this->render('listaForm_no_mh.html.twig',[
            'form'=> $form->createView(),
                ]);
    }
    /**
     *@Route("/listat_export")
     */
    public function listaForm3(Request $request): Response {
        $form = $this->createForm(ListaType::class, new ListaGenerate());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            return $this->render('listaGenerate.html.twig',[
            'listarivit'=>$this->listaService->makeList3($form->getData()->getString())
                ]);
            
        }
        
        return $this->render('listaForm_export.html.twig',[
            'form'=> $form->createView(),
                ]);
    }
    
    /**
     * @Route("/shitlist/")
     */
    public function shitLisDefault(): Response {
        return $this->redirect("/shitlist/7/2/5000");
    }
    
    /**
     *@Route("/shitlist/{days}/{maxprice}/{maxnormal}")
     */
    public function shitList(int $days, int $maxprice,int $maxnormal, Request $request): Response {
        $days = intval($days);
        $maxprice = intval($maxprice);
        $maxnormal = intval($maxnormal);
        return $this->render('listaGenerate.html.twig',[
            'listarivit'=>$this->listaService->makeShitList($days,$maxprice,$maxnormal)
                ]);
        
    }
 
}
