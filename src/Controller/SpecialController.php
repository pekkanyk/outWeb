<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OutletTuoteService;
use App\Service\UpdateStatsService;
use App\Form\Type\DayStatsType;
use App\Model\Search2Dates;

class SpecialController extends AbstractController
{
    private OutletTuoteService $outletTuoteService;
    private UpdateStatsService $updateStatsService;
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
    }
       
    
    
    /**
     * @Route("/special")
     */
    public function special(): Response
    {
        
        return $this->render('special.html.twig',[
        
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    
    /**
     * @Route("/bstats")
     */
    public function bstats(Request $request): Response
    {
        $alkaen = "2021-01-01";
        $asti = "2021-12-31";
        $form = $this->createForm(DayStatsType::class,new Search2Dates());
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $alkaen = $form->getData()->getAlku();
                $asti = $form->getData()->getLoppu();
            }
        
        return $this->render('b-stats.html.twig',[
            'form'=> $form->createView(),
            'headerStats'=>$this->updateStatsService->getStats(),
            'bstats'=>$this->outletTuoteService->getBstats($alkaen,$asti)
            
            ]);
         
    }
    
}
