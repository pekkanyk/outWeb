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
use App\Form\Type\LavapaikkaType;
use App\Entity\Lavapaikka;
use App\Service\LavapaikkaService;

class SpecialController extends AbstractController
{
    private OutletTuoteService $outletTuoteService;
    private UpdateStatsService $updateStatsService;
    private LavapaikkaService $lavapaikkaService;
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService, LavapaikkaService $lavapaikkaService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
        $this->lavapaikkaService = $lavapaikkaService;
    }
       
    
    
    /**
     * @Route("/lavapaikat/add")
     */
    public function addLavapaikka(Request $request): Response
    {
        $form = $this->createForm(LavapaikkaType::class,new Lavapaikka());
            $form->handleRequest($request);
            $ok = "";
            if($form->isSubmitted() && $form->isValid()){
                
                $lavapaikka = new Lavapaikka();
                $lavapaikka->setKaytava($form->getData()->getKaytava());
                $lavapaikka->setVali($form->getData()->getVali());
                $lavapaikka->setTaso($form->getData()->getTaso());
                $lavapaikka->setReuna($form->getData()->getReuna());
                $lavapaikka->setUsable(true);
                $lavapaikka->setId($lavapaikka->printName());
                if ($this->lavapaikkaService->find($lavapaikka->getId()) == null){
                    $this->lavapaikkaService->add($lavapaikka);
                    $ok=$lavapaikka->printName();
                }
                else {
                    $ok="Olemassa jo!";
                }
            }
        return $this->render('lava_add.html.twig',[
            'form'=> $form->createView(),
            'text'=> $ok,
            'headerStats'=>$this->updateStatsService->getStats()
            ]);
         
    }
    /**
     * @Route("/lavapaikat/")
     */
    public function lavapaikatDefault(): Response
    {
        
        return $this->redirect("/lavapaikat/3/0");
    }
    /**
     * @Route("/lavapaikat/edit")
     */
    public function editLavapaikka(Request $request): Response
    {
        $lp = $request->get('lava_text');
        $usage = $request->get('usage');
        $sisalto = $request->get('sisalto');
        $this->lavapaikkaService->editUsage($lp,$usage,$sisalto);
        
        $referer = $request->headers->get('referer');
        if($referer==null){
            $referer = "/";
        }
        return $this->redirect($referer);
         
    }
    
    /**
     * @Route("/lavapaikat/disenable/{lavapaikka}")
     */
    public function enadisableLavapaikka(String $lavapaikka): Response
    {
        $this->lavapaikkaService->enableOrDisable($lavapaikka);
        
        
        return $this->redirect("/lavapaikat");
    }
    
    
    /**
     * @Route("/lavapaikat/{kaytava}/{puoli}")
     */
        
    public function lavapaikat(int $kaytava, int $puoli): Response
    {
        
        $kaikkiLavapaikat = [];
        /*
        for ($i=5;$i>0;$i--){
            $lavapaikkaRivi = $this->lavapaikkaService->getTaso($kaytava, $i);
            $kaikkiLavapaikat[]=$this->toinenpuoli($lavapaikkaRivi, $puoli);
        }
        */
        for ($i=5;$i>0;$i--){
            if ($puoli == 1){
                $kaikkiLavapaikat[] = $this->lavapaikkaService->getOddTaso($kaytava, $i);
                
            }
            else{
                $kaikkiLavapaikat[] = $this->lavapaikkaService->getEvenTaso($kaytava, $i);
            }
            
        }
        
        return $this->render('lava_list.html.twig',[
            'kaytava'=>$kaytava,
            'puoli'=>$puoli,
            'lavapaikat'=> $kaikkiLavapaikat,
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
    
    private function toinenpuoli($lavapaikkaRivi, $puoli){
        $lavapaikkarivit =[];
        for ($i=0;$i<count($lavapaikkaRivi);$i++){
            if ($puoli ==1){
                if (!($lavapaikkaRivi[$i]->getVali() % 2 ==0)){
                    $lavapaikkarivit[]=$lavapaikkaRivi[$i];
                }
            }
            else{
                if ($lavapaikkaRivi[$i]->getVali() % 2 ==0){
                    $lavapaikkarivit[]=$lavapaikkaRivi[$i];
                }
            }
        }
        if ($puoli ==0){
            return array_reverse($lavapaikkarivit);
        }
        else {
        return $lavapaikkarivit;
        }
    }
    
}
