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
use App\Service\ReloadService;

class ReloadController extends AbstractController{
    /**
     *@Route("/reload")
     */
    public function reloadProducts(ReloadService $reloadService): Response {
        $outProducts = $reloadService->reloadProducts();
        return new Response(print_r($outProducts));
    }

}
