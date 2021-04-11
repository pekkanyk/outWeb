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

class DefaultController extends AbstractController{
    /**
     *@Route("/")
     */
    public function index(): Response
    {
        $number1 = random_int(0, 100);
        $number2 = random_int(0,100);
        return $this->render('index.html.twig',[
            'eka_numero'=>$number1,
            'toka_numero'=>$number2,
            ]);
    } 
}
