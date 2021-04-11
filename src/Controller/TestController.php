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

class TestController extends AbstractController{
    /**
     *@Route("/test/number")
     */
    public function number(): Response
    {
        $number = random_int(0, 100);
        /*return new Response(
                '<html><body>Random numero: '.$number.'</body></html>'
        );
         * 
         */
        return $this->render('test/number.html.twig',['number'=>$number]);
    }
}
