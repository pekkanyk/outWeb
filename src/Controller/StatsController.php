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
    public function dailystats(Request $request): Response
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
    /**
     * @Route("/stock/")
     */
    public function stockWithout(): Response
    {   
        //$today = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        //return $this->redirect("/stock/".$today->format("Y-m-d"));
        return $this->redirect("/stock/0");
    }
    /**
     * @Route("/stock/{day}"), name="stock"
     */
    public function stock($day,Request $request): Response
    {
        $day = $this->validateDate($day);
        if($day=="0" || $day==null){
            $asti = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
            $alkaen = (new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P30D'));
        }
        else {
            $asti = new \DateTime($day->format("Y-m-d"), new \DateTimeZone('Europe/Helsinki'));
            $alkaen = $asti;
        }
        
        $form = $this->createForm(DayStatsType::class,new Search2Dates($alkaen->format("Y-m-d"),$asti->format("Y-m-d")));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //return $this->redirect("/daystats/");
            $alkaen = $form->getData()->getAlku();
            $asti = $form->getData()->getLoppu();
        }
        //$today = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $daystats = $this->updateStatsService->getDayStats($alkaen,$asti);
        $chartArr = [['Aika','Aktiivisia']];
        for ($i=0;$i<count($daystats);$i++){
            array_push($chartArr,[$daystats[$i]->getTimestamp()->format('d.m.Y H:i'),$daystats[$i]->getTotalItems()]);
        }
        
	$chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
        $chart->getData()->setArrayToDataTable($chartArr);

        $chart->getOptions()->getChart()->setTitle('Tuotteita yhteensä, '.$alkaen->format('d.m.Y')." - ".$asti->format('d.m.Y'));
        $chart->getOptions()
            ->setHeight(400)
            ->setWidth(1200)
            ->setSeries([['axis' => 'Aktiivisia']])
            ->setAxes(['y' => ['Aktiivisia' => ['label' => 'Aktiivisia (kpl)']]]);

        //return new Response(print_r($daystats));
        return $this->render('stock.html.twig',[
            'form'=> $form->createView(),
            'chart'=> $chart,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);

    }
    
    private function validateDate($date, $format = 'Y-m-d'){
    $d = \DateTime::createFromFormat($format, $date);
    //return $d && $d->format($format) === $date;
    if ($d && $d->format($format) === $date){ return $d;}
    else {return null;}
    }
    
    /**
     * @Route("/dbstats")
     */
    public function dbstats(): Response
    {
        $dbstats = $this->outletTuoteService->dbStats();
        return $this->render('dbstats.html.twig',[
            'stats'=>$dbstats,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
        
    }
    
    /**
     * @Route("/invis/{digits}")
     */
    public function hyllyPaikka(int $digits): Response
    {
        $vikat = intval($digits);
        $tuotteet = $this->outletTuoteService->hyllypaikkaHaku($vikat);
        return $this->render('inventaario_print.html.twig',[
            'tuotteet'=>$tuotteet,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    /**
     * @Route("/invis")
     */
    public function inventaariolista(): Response
    {
        $hyllypaikkaCount = $this->outletTuoteService->hyllyStats();
        return $this->render('inventaario.html.twig',[
            'hyllypaikkaCount'=> $hyllypaikkaCount,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
}
