<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OutletTuoteService;
use App\Service\UpdateStatsService;
use App\Service\BookmarksService;
use App\Service\UserService;
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
    private BookmarksService $bookmarksService;
    private UserService $userService;
    public function __construct(OutletTuoteService $outletTuoteService, UpdateStatsService $updateStatsService, BookmarksService $bookmarksService, UserService $userService) {
        $this->outletTuoteService = $outletTuoteService;
        $this->updateStatsService = $updateStatsService;
        $this->bookmarksService = $bookmarksService;
        $this->userService = $userService;
    }
    /**
     * @Route("/search/outid/")
     */
    public function outIdPage(): Response {
        return $this->redirect("/search/outid/0");
    }
        
    /**
     * @Route("/search/outid/{outId}", name="outlet_tuote")
     */
    public function show(int $outId, Request $request): Response
    {
        $outId = intval($outId);
        $dbUser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        $userId = $dbUser->getId();
	$today = (new \DateTime())->format('Y-m-d');
        $outletTuote = $this->outletTuoteService->getOutletTuote($outId);
        if ($outletTuote==null){ $outletTuote = $this->outletTuoteService->makeDummy($outId);}
        $active = $this->outletTuoteService->getActiveWithPid($outletTuote->getPid());
        $deleted = $this->outletTuoteService->getDeletedWithPid($outletTuote->getPid());
        $form = $this->createForm(OutIdType::class,new SearchOutId());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $this->redirect("/search/outid/".$form->getData()->getOutId());
        }
        return $this->render('outlet_tuote.html.twig',[
            'bookmarked'=>$this->bookmarksService->isBookmarked($outId, $userId),
            'headerStats'=>$this->updateStatsService->getStats(),
            'form'=> $form->createView(),
            'today'=>$today,
            'outletTuote'=> $outletTuote,
            'active'=>$active,
            'deleted'=>$deleted,
            'pidInfo'=> $this->outletTuoteService->getPidInfo($outletTuote->getPid())]
                );
    }
    /**
     * @Route("/search/pid/")
     */
    public function pidPage(): Response {
        return $this->redirect("/search/pid/0");
    }
    
    /**
     * @Route("/search/noinfo")
     */
    public function noInfo(): Response
    {
        $products = $this->outletTuoteService->noInfo();
            
        return $this->render('noinfo.html.twig',[
            'outTuotteet'=>$products,
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        
    }
    /**
     * @Route("/search/poisto")
     */
    public function poisto(): Response
    {
        $products = $this->outletTuoteService->poisto();
            
        return $this->render('noinfo.html.twig',[
            'outTuotteet'=>$products,
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        
    }
    /**
     * @Route("/search/dumppi")
     */
    public function dumppi(): Response
    {
        $products = $this->outletTuoteService->dumppi();
            
        return $this->render('noinfo.html.twig',[
            'outTuotteet'=>$products,
            'headerStats'=>$this->updateStatsService->getStats()
        
            ]);
        
    }
    /**
     * @Route("/search/pid/{pid}", name="pid_infosivu")
     */
    public function showPid($pid, Request $request): Response
    {
        $pid = intval($pid);
        $dbUser = $this->userService->findUsername($this->getUser()->getUsername())[0];
        $userId = $dbUser->getId();
	$today = (new \DateTime())->format('Y-m-d');
        $pidStats = $this->outletTuoteService->getPidStats($pid);
        $active = $this->outletTuoteService->getActiveWithPid($pid);
        $deleted = $this->outletTuoteService->getDeletedWithPid($pid);
        $form = $this->createForm(PidType::class,new SearchPid());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            return $this->redirect("/search/pid/".$form->getData()->getPid());
        }
        return $this->render('pid_tuote.html.twig',[
            'pricewatch'=>$this->bookmarksService->isPricewatch($pid, $userId),
            'headerStats'=>$this->updateStatsService->getStats(),
            'today'=>$today,
            'pidStats'=>$pidStats,
            'active'=>$active,
            'form'=> $form->createView(),
            'deleted'=>$deleted
            ]
                );
    }
    
    /**
     * @Route("/search/firstseen/")
     */
    public function fistSeenPage(): Response{
        return $this->redirect("/search/firstseen/0");
    }
    /**
     * @Route("/search/firstseen/{date}")
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
            return $this->redirect("/search/firstseen/".$form->getData()->getDate()->format('Y-m-d'));
        }
        return $this->render('first_seen.html.twig',[
            'headerStats'=>$this->updateStatsService->getStats(),
            'form'=> $form->createView(),
            'today'=>$today,
            'date'=>$date,
            'active'=>$active,
            'deleted'=>$deleted]
                );
    }
    
    private function validateDate($date, $format = 'Y-m-d'){
    $d = \DateTime::createFromFormat($format, $date);
    //return $d && $d->format($format) === $date;
    if ($d && $d->format($format) === $date){ return $d;}
    else {return new \DateTime();}
    }
}
