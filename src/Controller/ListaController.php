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


class ListaController extends AbstractController{
    private ListaService $listaService;
    public function __construct(ListaService $listaService) {
        $this->listaService = $listaService;
    }
    /**
     *@Route("/listat")
     */
    public function listaForm(): Response {
        
        return $this->render('listaForm.html.twig');
    }
    /**
     *@Route("/listat/generated")
     */
    public function listaCreate(): Response {
        return $this->render('listaCreated.html.twig',[
            'today'=>$todaystr,
            'toka_numero'=>$number2,
            ]);
    }
    
    
}
