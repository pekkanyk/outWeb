<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OutletTuoteService;
use App\Service\UpdateStatsService;
use App\Form\Type\PidType;
use App\Form\Type\OutIdType;
use App\Form\Type\DateHakuType;
use App\Model\SearchPid;
use App\Model\SearchOutId;
use App\Model\SearchDate;
use Symfony\Component\HttpFoundation\Request;


class OutletTuoteController extends AbstractController
{
    private OutletTuoteService $outletTuoteService;
    private UpdateStatsService $updateStatsService;
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
    }
    /**
     * @Route("/outid/")
     */
    public function outIdPage(): Response {
        return $this->redirect("/outid/0");
    }
        
    /**
     * @Route("/outid/{outId}", name="outlet_tuote")
     */
    public function show(int $outId, Request $request): Response
    {
        $outId = intval($outId);
	$today = (new \DateTime())->format('Y-m-d');
        $outletTuote = $this->outletTuoteService->getOutletTuote($outId);
        if ($outletTuote==null){ $outletTuote = $this->outletTuoteService->makeDummy($outId);}
        $active = $this->outletTuoteService->getActiveWithPid($outletTuote->getPid());
        $deleted = $this->outletTuoteService->getDeletedWithPid($outletTuote->getPid());
        $form = $this->createForm(OutIdType::class,new SearchOutId());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $this->redirect("/outid/".$form->getData()->getOutId());
        }
        return $this->render('outlet_tuote.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats(),
            'form'=> $form->createView(),
            'today'=>$today,
            'outletTuote'=> $outletTuote,
            'active'=>$active,
            'deleted'=>$deleted,
            'activeLkm'=>count($active),
            'deletedLkm'=>count($deleted),
            'pidInfo'=> $this->outletTuoteService->getPidInfo($outletTuote->getPid())]
                );
    }
    /**
     * @Route("/pid/")
     */
    public function pidPage(): Response {
        return $this->redirect("/pid/0");
    }
    /**
     * @Route("/pid/{pid}", name="pid_infosivu")
     */
    public function showPid($pid, Request $request): Response
    {
        $pid = intval($pid);
	$today = (new \DateTime())->format('Y-m-d');
        $pidStats = $this->outletTuoteService->getPidStats($pid);
        $active = $this->outletTuoteService->getActiveWithPid($pid);
        $deleted = $this->outletTuoteService->getDeletedWithPid($pid);
        $form = $this->createForm(PidType::class,new SearchPid());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $this->redirect("/pid/".$form->getData()->getPid());
        }
        return $this->render('pid_tuote.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats(),
            'today'=>$today,
            'pidStats'=>$pidStats,
            'active'=>$active,
            'activeLkm'=>count($active),
            'deletedLkm'=>count($deleted),
            'form'=> $form->createView(),
            'deleted'=>$deleted
            ]
                );
    }
    
    /**
     * @Route("/firstseen/")
     */
    public function fistSeenPage(): Response{
        return $this->redirect("/firstseen/0");
    }
    /**
     * @Route("/firstseen/{date}")
     */
    public function firstSeen($date, Request $request): Response
    {
        $today = (new \DateTime())->format('Y-m-d');
        $date = $this->validateDate($date);
        $active = $this->outletTuoteService->getActiveFirstSeen($date);
        $deleted = $this->outletTuoteService->getDeletedFirstSeen($date);
        $form = $this->createForm(DateHakuType::class,new SearchDate());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $this->redirect("/firstseen/".$form->getData()->getDate()->format('Y-m-d'));
        }
        return $this->render('first_seen.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats(),
            'form'=> $form->createView(),
            'today'=>$today,
            'date'=>$date,
            'active'=>$active,
            'deleted'=>$deleted,
            'activeLkm'=>count($active),
            'deletedLkm'=>count($deleted)]
                );
    }
    
    private function validateDate($date, $format = 'Y-m-d'){
    $d = \DateTime::createFromFormat($format, $date);
    //return $d && $d->format($format) === $date;
    if ($d && $d->format($format) === $date){ return $d;}
    else {return new \DateTime();}
}
}
