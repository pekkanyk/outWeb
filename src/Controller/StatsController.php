<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OutletTuoteService;
use App\Service\UpdateStatsService;

class StatsController extends AbstractController
{
    private OutletTuoteService $outletTuoteService;
    private UpdateStatsService $updateStatsService;
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
    }
       
    /**
     * @Route("/daystats/")
     */
    public function dailystats(): Response
    {
	
        return $this->render('daystats.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
    }

}
