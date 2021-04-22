<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\OutletTuote;
use App\Service\OutletTuoteService;

class OutletTuoteController extends AbstractController
{
    private OutletTuoteService $outletTuoteService;
    public function __construct(OutletTuoteService $outletTuoteService) {
        $this->outletTuoteService = $outletTuoteService;
    }
    /**
     * @Route("/outid/{outId}", name="outlet_tuote")
     */
    public function show(int $outId): Response
    {
	
        $today = (new \DateTime())->format('Y-m-d');
        $outIdInfo = $this->outletTuoteService->getOutIdInfo($outId);
        return $this->render('outlet_tuote.html.twig',[
            'outTuotteet'=>$outIdInfo,
            'today'=>$today,
            'active'=>count($outIdInfo[1]),
            'deleted'=>count($outIdInfo[2])]
                );
        //return new Response (print_r($this->outletTuoteService->getOutIdInfo($outId)));
    }
}
