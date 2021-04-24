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
        $outletTuote = $this->outletTuoteService->getOutletTuote($outId);
        $active = $this->outletTuoteService->getActiveWithPid($outletTuote->getPid());
        $deleted = $this->outletTuoteService->getDeletedWithPid($outletTuote->getPid());
        return $this->render('outlet_tuote.html.twig',[
            'today'=>$today,
            'outletTuote'=> $outletTuote,
            'active'=>$active,
            'deleted'=>$deleted,
            'activeLkm'=>count($active),
            'deletedLkm'=>count($deleted),
            'pidInfo'=> $this->outletTuoteService->getPidInfo($outletTuote->getPid())]
                );
        //return new Response (print_r($this->outletTuoteService->getOutIdInfo($outId)));
    }
    /**
     * @Route("/pid/{pid}", name="pid_infosivu")
     */
    public function showPid(int $pid): Response
    {
	$today = (new \DateTime())->format('Y-m-d');
        $pidStats = $this->outletTuoteService->getPidStats($pid);
        //$active = $this->outletTuoteService->getActiveWithPid($outletTuote->getPid());
        //$deleted = $this->outletTuoteService->getDeletedWithPid($outletTuote->getPid());
        return $this->render('pid_tuote.html.twig',[
            'today'=>$today,
            'pidStats'=>$pidStats
            ]
                );
    }
}
