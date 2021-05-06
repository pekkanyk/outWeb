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
     * @Route("/special/")
     */
    public function onlyC(Request $request): Response
    {
        $asti = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        $alkaen = (new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P30D'));
        $form = $this->createForm(DayStatsType::class,new Search2Dates());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //return $this->redirect("/daystats/");
            $alkaen = $form->getData()->getAlku();
            $asti = $form->getData()->getLoppu();
        }
	$dates = $this->outletTuoteService->getDates($alkaen, $asti);
        $datestats_rows = [];
        for ($i=0;$i<count($dates);$i++){
            $row = $this->outletTuoteService->getNumbersFor($dates[$i]['deleted']);
            $row_with_date = array_merge($dates[$i],$row);
            array_push($datestats_rows,$row_with_date);
        }
        
        $chartArr = [['Päivä','Deleted','Uusia','Total']];
        for ($i=count($datestats_rows)-1;$i>=0;$i--){
            array_push($chartArr,[$datestats_rows[$i]['deleted']->format('d.m.Y'),
                                    $datestats_rows[$i]['deletedCount'],
                                    $datestats_rows[$i]['firstCount'],
                                    intval($datestats_rows[$i]['avgCount'])
                                    ]);
        }
        
	$chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
        $chart->getData()->setArrayToDataTable($chartArr);

        $chart->getOptions()
            ->setHeight(400)
            ->setWidth(1200);
            //->setSeries([['axis' => 'Deleted'],
                //        ['axis' =>'Uusia'],
                //        ['axis'=>'Total']
              //  ]) ;
        
        return $this->render('daystats.html.twig',[
            'chart'=> $chart,
            'form'=> $form->createView(),
            'dates'=> $datestats_rows,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    
}
