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
use App\Model\WeekStats;

class StatsController extends AbstractController
{
    private OutletTuoteService $outletTuoteService;
    private UpdateStatsService $updateStatsService;
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
    }
    
    /**
     * @Route("/stats/daystats/")
     */
    public function dailystats(Request $request): Response
    {
        $asti = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        $asti->setTime(23,59,59);
        $alkaen = (new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P14D'));
        $alkaen->setTime(23,59,59);
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
        
        //$chartArr = [['Päivä','Deleted','Uusia','Total']];
        $chartArr = [['Päivä','Deleted','Uusia']];
        for ($i=count($datestats_rows)-1;$i>=0;$i--){
            //array_push($chartArr,[$datestats_rows[$i]['deleted']->format('d.m.Y'),
            array_push($chartArr,[$datestats_rows[$i]['deleted'],
                                    $datestats_rows[$i]['deletedCount'],
                                    $datestats_rows[$i]['firstCount'],
                                    //intval($datestats_rows[$i]['avgCount'])
                                    ]);
        }
        /*
	$chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
        $chart->getData()->setArrayToDataTable($chartArr);

        $chart->getOptions()
            ->setHeight(400)
            ->setWidth(1200);
        */
        $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart();
        $chart->getData()->setArrayToDataTable($chartArr);
        $chart->getOptions()->getChart()
            ->setTitle('Uusia & Poistettuja');
            
        $chart->getOptions()
        ->setBars('vertical')
        ->setHeight(400)
        ->setWidth(1200)
        ->setColors(['#2222FF','#FF2222'])
        ->getVAxis()
        ->setFormat('decimal');
        
        
        return $this->render('daystats.html.twig',[
            'chart'=> $chart,
            'form'=> $form->createView(),
            'dates'=> $datestats_rows,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
       
    /**
     * @Route("/stats/weekstats/")
     */
    public function weeklyStats(Request $request): Response
    {
        $asti = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        $asti->setTime(23,59,59);
        $alkaen = (new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P90D'));
        $alkaen->setTime(23,59,59);
        $form = $this->createForm(DayStatsType::class,new Search2Dates());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //return $this->redirect("/daystats/");
            $alkaen = $form->getData()->getAlku();
            $asti = $form->getData()->getLoppu();
        }
        
        $weeks = [];
        $weekStart = $alkaen;
        while ($weekStart->format("D")!="Mon"){
            $weekStart = $weekStart->add(new \DateInterval('P1D'));
        }
        $weekEnd = (new \DateTime($weekStart->format("Y-m-d")))->add(new \DateInterval('P6D'));
        $weekStart->setTime(23,59,59);
        $weekEnd->setTime(23,59,59);
        
        while ($weekStart < $asti){
            
            $week = new WeekStats($weekStart,$this->outletTuoteService->countDeleted($weekStart,$weekEnd),$this->outletTuoteService->countFirstSeen($weekStart,$weekEnd));
            $week->setSumma_uudet($this->outletTuoteService->sumFirstseenOutPrice($weekStart,$weekEnd));
            $week->setSumma_poistuneet($this->outletTuoteService->sumDeletedOutPrice($weekStart,$weekEnd));
            $week->setSumma_norPrice($this->outletTuoteService->sumDeletedNorPrice($weekStart,$weekEnd));
            $weeks[] = $week;
            $weekStart = (new \DateTime($weekEnd->format("Y-m-d"), new \DateTimeZone('Europe/Helsinki')))->add(new \DateInterval('P1D'));
            $weekEnd = (new \DateTime($weekStart->format("Y-m-d"), new \DateTimeZone('Europe/Helsinki')))->add(new \DateInterval('P6D'));
            $weekStart->setTime(23,59,59);
            $weekEnd->setTime(23,59,59);
        }
        $weeks = array_reverse($weeks);
        /*
	$dates = $this->outletTuoteService->getDates($alkaen, $asti);
        $datestats_rows = [];
        for ($i=0;$i<count($dates);$i++){
            $row = $this->outletTuoteService->getNumbersFor($dates[$i]['deleted']);
            $row_with_date = array_merge($dates[$i],$row);
            array_push($datestats_rows,$row_with_date);
        }
        */
        
        $chartArr = [['Viikko','Deleted','Uusia','SumUudet','SumDeleted']];
        for ($i=0;$i<count($weeks);$i++){
            array_push($chartArr,[$weeks[$i]->getAlku(),
                                    $weeks[$i]->getPoistuneet(),
                                    $weeks[$i]->getUudet(),
                                    floor($weeks[$i]->getSumma_uudet()),
                                    floor($weeks[$i]->getSumma_poistuneet()),
                                    ]);
        }
        
        $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart();
        $chart->getData()->setArrayToDataTable($chartArr);
        
        $chart->getOptions()->getChart()
            ->setTitle('Uusia & Poistettuja');
            
        $chart->getOptions()
        ->setBars('vertical')
        ->setHeight(400)
        ->setWidth(1200)
        ->setSeries([['axis' => 'Kpl'],
                     ['axis' => 'Kpl'],
                     ['axis' => 'Summa'],
                    ['axis'=>'Summa']])
            ->setAxes(['y' => ['Kpl' => ['label' => 'kpl'],
                            'Summa' => ['side' => 'right','label' => 'Summa (€)'],
                            ]])
        ->setColors(['#2222FF','#FF2222','#1b9e77','#FFD700'])
        ->getVAxis()
        ->setFormat('decimal');
        
        
        return $this->render('weekstats.html.twig',[
            'chart'=> $chart,
            'form'=> $form->createView(),
            'weeks'=> $weeks,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    
    /**
     * @Route("/stats/stock/")
     */
    public function stockWithout(): Response
    {   
        //$today = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        //return $this->redirect("/stock/".$today->format("Y-m-d"));
        return $this->redirect("/stats/stock/0");
    }
    /**
     * @Route("/stats/stock/{day}"), name="stock"
     */
    public function stock($day,Request $request): Response
    {
        $day = $this->validateDate($day);
        if($day=="0" || $day==null){
            $asti = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
            $asti->setTime(23,59,59);
            $alkaen = (new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P14D'));
            $alkaen->setTime(23,59,59);
        }
        else {
            $asti = new \DateTime($day->format("Y-m-d"), new \DateTimeZone('Europe/Helsinki'));
            $asti->setTime(23,59,59);
            $alkaen = $asti;
        }
        
        //$form = $this->createForm(DayStatsType::class,new Search2Dates($alkaen->format("Y-m-d"),$asti->format("Y-m-d")));
        $form = $this->createForm(DayStatsType::class,new Search2Dates());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //return $this->redirect("/daystats/");
            $alkaen = $form->getData()->getAlku();
            $asti = $form->getData()->getLoppu();
        }
        //$today = date_create("now", new \DateTimeZone('Europe/Helsinki'));
        $daystats = $this->updateStatsService->getDayStats($alkaen,$asti);
        $chartArr = [['Aika','Aktiivisia','Summa']];
        $filtered = 0;
        for ($i=0;$i<count($daystats);$i++){
            if ($i>0){
                $lastSum = $daystats[$i-1]->getSum();
                if ($daystats[$i]->getSum() > $lastSum+10000 && $filtered == 0){
                    $sum = $lastSum+200;
                    $filtered = 1;
                }
                elseif($daystats[$i]->getSum() < $lastSum-10000 && $filtered == 0){
                    $sum = $lastSum-200;
                    $filtered = 1;
                }
                else {
                    $sum = $daystats[$i]->getSum();
                    $filtered = 0;
                }
            }
            else {
                $sum = $daystats[$i]->getSum();
            }
            array_push($chartArr,[$daystats[$i]->getTimestamp(),$daystats[$i]->getTotalItems(),$sum]);
        }
        
	$chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
        $chart->getData()->setArrayToDataTable($chartArr);

        $chart->getOptions()->getChart()->setTitle('Tuotteita yhteensä, '.$alkaen->format('d.m.Y')." - ".$asti->format('d.m.Y'));
        $chart->getOptions()
            ->setHeight(400)
            ->setWidth(1200)
            ->setSeries([['axis' => 'Aktiivisia'],
                ['axis'=>'Summa']])
            ->setAxes(['y' => ['Aktiivisia' => ['label' => 'Aktiivisia (kpl)'],
                            'Summa' => ['side' => 'right','label' => 'Summa (€)']
                            ]]);
        $chart->getOptions()
                ->getVAxis()
                ->setFormat('');
        
        $chart2Arr = [['Aika','Deleted','Uusia']];
        for ($i=0;$i<count($daystats);$i++){
            if ($i>0){
                $lastDeleted = $daystats[$i-1]->getDeleted();
                if ($daystats[$i]->getDeleted() > $lastDeleted+200){
                    $deleted = $lastDeleted+10;
                }
                else {
                    $deleted = $daystats[$i]->getDeleted();
                }  
            }
            else {
                $deleted = $daystats[$i]->getDeleted();
            }
            //array_push($chart2Arr,[$daystats[$i]->getTimestamp(),$daystats[$i]->getDeleted(),$daystats[$i]->getNew()]);
            array_push($chart2Arr,[$daystats[$i]->getTimestamp(),$deleted,$daystats[$i]->getNew()]);
        }
        
	$chart2 = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
        $chart2->getData()->setArrayToDataTable($chart2Arr);

        $chart2->getOptions()->getChart()->setTitle('Poistuneita & uusia päivän aikana, '.$alkaen->format('d.m.Y')." - ".$asti->format('d.m.Y'));
        $chart2->getOptions()
            ->setHeight(400)
            ->setWidth(1200);
                /*
            ->setSeries([['axis' => 'Deleted'],
                ['axis'=>'Uusia']])
            ->setAxes(['y' => ['Deleted' => ['label' => 'Poistuneita (kpl)'],
                            'Uusia' => ['side' => 'right','label' => 'Uusia (kpl)']
                            ]]);
                 * 
                 */
                 
        $chart2->getOptions()
                ->getVAxis()
                ->setFormat('');
        return $this->render('stock.html.twig',[
            'form'=> $form->createView(),
            'chart'=> $chart,
            'chart2'=>$chart2,
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
     * @Route("/stats/dbstats")
     */
    public function dbstats(Request $request): Response
    {
        $asti = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        $asti->setTime(23,59,59);
        $alkaen = date_create_from_format('d-m-Y', '01-01-2010');
        $alkaen->setTime(23,59,59);
        $form = $this->createForm(DayStatsType::class,new Search2Dates());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //return $this->redirect("/daystats/");
            $alkaen = $form->getData()->getAlku();
            $asti = $form->getData()->getLoppu();
        }
        $dbstats = $this->outletTuoteService->dbStats($alkaen,$asti);
        return $this->render('dbstats.html.twig',[
            'alkaen'=>$alkaen,
            'asti'=>$asti,
            'headerStats'=>$this->updateStatsService->getStats(),
            'form'=> $form->createView(),
            'stats'=>$dbstats,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
        
    }
    
    /**
     * @Route("/stats/invis/{digits}")
     */
    public function hyllyPaikka($digits): Response
    {
        //$digits = substr($digits, 1);
        if (strlen($digits)==1){
            $kerroin = 10;
        }
        else{
            $kerroin = 100;
        }
        $vikat = intval($digits);
        $next = ($digits+($kerroin/10))%$kerroin;
        $tuotteet = $this->outletTuoteService->hyllypaikkaHaku($vikat, $kerroin);
        return $this->render('inventaario_print.html.twig',[
            'tuotteet'=>$tuotteet,
            'next'=>$next,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    /**
     * @Route("/stats/invis")
     */
    public function inventaariolista(): Response
    {
        $hyllypaikkaCount = $this->outletTuoteService->hyllyStats();
        return $this->render('inventaario.html.twig',[
            'hyllypaikkaCount'=> $hyllypaikkaCount,
            'isotNumerot'=>$this->outletTuoteService->hyllyIsotNumerot(),
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    /**
     * @Route("/stats/pricepros")
     */
    public function pricepros(Request $request): Response
    {
        $asti = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        $asti->setTime(23,59,59);
        $alkaen = date_create_from_format('d-m-Y', '01-01-2010');
        $alkaen->setTime(23,59,59);
        $form = $this->createForm(DayStatsType::class,new Search2Dates());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //return $this->redirect("/daystats/");
            $alkaen = $form->getData()->getAlku();
            $asti = $form->getData()->getLoppu();
        }
        
        return $this->render('pros_price.html.twig',[
            'alkaen'=>$alkaen,
            'asti'=>$asti,
            'headerStats'=>$this->updateStatsService->getStats(),
            'form'=> $form->createView(),
            'prices'=>$this->outletTuoteService->prosPrice($alkaen,$asti)
            ]);
         
    }
    
    /**
     * @Route("/stats/distinct")
     */
    public function distinctProds(): Response
    {
        
        return $this->render('distinct.html.twig',[
            'topDistinct'=>$this->outletTuoteService->distinctProducts(),
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    
    /**
     * @Route("/stats/dayspread")
     */
    public function daySpread(): Response
    {
        $chartData = $this->outletTuoteService->daySpread();
        $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart();
        $chartArr = [ ['Days', 'Kpl'] ];
        
        for ($i=0;$i<count($chartData);$i++){
            array_push($chartArr,[$chartData[$i]['Days'],$chartData[$i]['CountOf']]);
        }
        $chart->getData()->setArrayToDataTable($chartArr);
        $chart->getOptions()->getChart()
            ->setTitle('Päiviä hinnanmuutoksesta');
            
        $chart->getOptions()
        ->setBars('vertical')
        ->setHeight(400)
        ->setWidth(1200)
        ->setColors(['#1b9e77'])
        ->getVAxis()
        ->setFormat('decimal');
        
        $chart2Data = $this->outletTuoteService->daySpreadFirstSeen();
        $chart2 = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart();
        $chart2Arr = [ ['Days', 'Kpl'] ];
        
        for ($i=0;$i<count($chart2Data);$i++){
            array_push($chart2Arr,[$chart2Data[$i]['Days'],$chart2Data[$i]['CountOf']]);
        }
        $chart2->getData()->setArrayToDataTable($chart2Arr);
        $chart2->getOptions()->getChart()
            ->setTitle('Päiviä akviivisena');
            
        $chart2->getOptions()
        ->setBars('vertical')
        ->setHeight(400)
        ->setWidth(1200)
        ->setColors(['#5b9e11'])
        ->getVAxis()
        ->setFormat('decimal');

        return $this->render('dayspread.html.twig',[
            'chart2'=> $chart2,
            'chart'=> $chart,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);

    }
}
