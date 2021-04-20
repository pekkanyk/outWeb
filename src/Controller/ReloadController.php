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
    private ReloadService $reloadService;
    public function __construct(ReloadService $reloadService) {
        $this->reloadService = $reloadService;
    }
    /**
     *@Route("/reload")
     */
    //public function reloadProducts(ReloadService $reloadService): Response {
    public function reloadProducts(): Response {
        //$entityManager = $this->getDoctrine()->getManager();
        $this->reloadService->updateDb();
        return new Response(print("done"));
    }

}
